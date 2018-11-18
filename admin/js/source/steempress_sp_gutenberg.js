const { assign } = lodash;
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { addFilter } = wp.hooks;
const { TextControl, PanelBody } = wp.components;
const { createHigherOrderComponent } = wp.compose;
const { InspectorControls } = wp.editor;

/**
* Override the default edit UI to include a new block inspector control for
    * adding our custom control.
*
* @param {function|Component} BlockEdit Original component.
*
* @return {string} Wrapped component.
*/
export const addMyCustomBlockControls = createHigherOrderComponent( ( BlockEdit ) => {

    return ( props ) => {

    // If this block supports scheduling and is currently selected, add our UI
    if ( isValidBlockType( props.name ) && props.isSelected ) {
        return (
            <Fragment>
            <BlockEdit { ...props } />
        <InspectorControls>
        <PanelBody title={ __( 'My Custom Panel Heading' ) }>
    <TextControl
        label={ __( 'My Custom Control' ) }
        help={ __( 'Some help text for my custom control.' ) }
        value={ props.attributes.scheduledStart || '' }
        onChange={ ( nextValue ) => {
            props.setAttributes( {
                scheduledStart: nextValue,
            } );
        } } />
        </PanelBody>
        </InspectorControls>
        </Fragment>
    );
    }

    return <BlockEdit { ...props } />;
};
}, 'addMyCustomBlockControls' );

addFilter( 'editor.BlockEdit', 'my-plugin/my-control', addMyCustomBlockControls );
/**
 * Is the passed block name one which supports our custom field?
 *
 * @param {string} name The name of the block.
 */
function isValidBlockType( name ) {

    const validBlockTypes = [
        'core/paragraph',
        'core/image',
        'core/heading',
    ];

    return validBlockTypes.includes( name );

}// end isValidBlockType()

/**
* Filters registered block settings, extending attributes with our custom data.
*
* @param {Object} settings Original block settings.
*
* @return {Object} Filtered block settings.
*/
export function addAttribute( settings ) {

    // If this is a valid block
    if ( isValidBlockType( settings.name ) ) {

        // Use Lodash's assign to gracefully handle if attributes are undefined
        settings.attributes = assign( settings.attributes, {
            scheduledStart: {
                type: 'string',
            },
        } );
    }

    return settings;

}// end addAttribute()

/**
 * Override props assigned to save component to inject our custom data.
 * This is only done if the component is a valid block type.
 *
 * @param {Object} extraProps Additional props applied to save element.
 * @param {Object} blockType  Block type.
 * @param {Object} attributes Current block attributes.
 *
 * @return {Object} Filtered props applied to save element.
 */
export function addSaveProps( extraProps, blockType, attributes ) {

    // If the current block is valid, add our prop.
    if ( isValidBlockType( blockType.name ) ) {
        extraProps.scheduledStart = attributes.scheduledStart;
    }

    return extraProps;

}// end addSaveProps()

addFilter( 'blocks.registerBlockType', 'my-plugin/add-attr', addAttribute );
addFilter( 'blocks.getSaveContent.extraProps', 'my-plugin/add-props', addSaveProps );

