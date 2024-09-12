(function() {

	/**
	 * Load WordPress dependencies
	 * This is the same as importing the dependencies in an ES2015+ environment
	 */
	let __ = wp.i18n.__;

	let createElement = window.wp.element.createElement;

	let useBlockProps 	  = window.wp.blockEditor.useBlockProps;
	let BlockControls 	  = window.wp.blockEditor.BlockControls;
	let AlignmentControl  = window.wp.blockEditor.AlignmentControl;
	let InspectorControls = window.wp.blockEditor.InspectorControls;

	let PanelBody   = window.wp.components.PanelBody;
	let TextControl = window.wp.components.TextControl;

	let registerBlockType = window.wp.blocks.registerBlockType;

	/**
	 * Every block starts by registering a new block type definition.
	 *
	 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
	 */
	registerBlockType( 'copyright-date/copyright-date-block', {

		/**
		 * The edit function describes the structure of your block in the context of the
		 * editor. This represents what the editor will render when the block is used.
		 *
		 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
		 *
		 * @return {Element} Element to render.
		 */
		edit: function( { attributes, setAttributes } ) {

			const { startingYear } = attributes;
			const currentYear = new Date().getFullYear().toString();

			function onChangeAlignment( newAlignment ) {
				setAttributes( { alignment: newAlignment === undefined ? 'none' : newAlignment } );
			}

			/**
			 * Create the text control for the starting year
			 * Will be added to the PanelBody
			 */
			const textControl = createElement(
				TextControl,
				{
					label: __( 'Starting Year', 'copyright-date-block' ),
					value: startingYear,
					onChange: ( newStartingYear ) => {
						setAttributes( { startingYear: newStartingYear } );
					}
				}
			);

			/**
			 * Create the panel body for the settings
			 * Includes the text control
			 * Will be added to the InspectorControls
			 */
			const panelBody = createElement(
				PanelBody,
				{
					title: __( 'Settings', 'copyright-date-block' ),
				},
				textControl
			);

			/**
			 * Create the inspector controls for the block
			 * Includes the panel body
			 * Will be added to the final block output
			 */
			const inspectorControls = createElement(
				InspectorControls,
				{},
				panelBody
			);

			/**
			 * Create the alignment control
			 * Will be added to the BlockControls
			 */
			const alignmentControl = createElement(
				AlignmentControl,
				{
					value: attributes.alignment,
					onChange: onChangeAlignment,
				}
			);

			/**
			 * Create the block controls
			 * Includes the alignment control
			 * Will be added to the final block output
			 */
			const blockControls = createElement(
				BlockControls,
				{
					key: 'controls'
				},
				alignmentControl
			);

			/**
			 * Create the paragraph with the copyright information in it
			 * Will be added to the final block output
			 */
			const copyrightParagraph = createElement(
				'p',
				{},
				"Copyright © " + startingYear + " " + currentYear,
			);

			/**
			 * Create the final block output
			 * Includes the block controls, inspector controls, and the paragraph with the copyright information
			 */
			return createElement(
				'div',
				useBlockProps(),
				blockControls,
				inspectorControls,
				copyrightParagraph,
			);
		},
		save: function( { attributes }  ) {

			const { startingYear } = attributes;
			const currentYear = new Date().getFullYear().toString();

			const copyrightParagraph = createElement(
				'p',
				{},
				'Copyright © ' + startingYear + ' ' + currentYear,
			)

			return createElement(
				'div',
				useBlockProps.save(),
				copyrightParagraph,
			);
		},
	} );
})();