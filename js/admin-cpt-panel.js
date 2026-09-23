/**
 * Native Gutenberg sidebar panel for the 5 custom post types' detail
 * fields (Layanan, Paket, Mentor, Testimoni, FAQ). Renders dynamically
 * from the schema + icon list localized as window.dqCptPanel, and reads/
 * writes meta through the block editor's own data store — no classic
 * meta box, no custom $_POST handling.
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.plugins || ! wp.editPost || ! window.dqCptPanel ) {
		return;
	}

	var registerPlugin = wp.plugins.registerPlugin;
	var PluginDocumentSettingPanel = wp.editPost.PluginDocumentSettingPanel;
	var el = wp.element.createElement;
	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;
	var components = wp.components;

	var CONFIG = window.dqCptPanel;
	var SCHEMA = CONFIG.schema || {};
	var ICONS = CONFIG.icons || {};
	var LABELS = CONFIG.labels || {};
	var PREVIEW_ANCHORS = CONFIG.previewAnchors || {};
	var HOME_URL = CONFIG.homeUrl || '/';
	var __ = ( wp.i18n && wp.i18n.__ ) || function ( text ) { return text; };

	/**
	 * Re-target WordPress' own (autosave-aware) post preview link at a
	 * homepage section anchor instead of the post's single-item permalink,
	 * keeping its preview_id/preview_nonce query args intact so
	 * dq_maybe_preview_post() (inc/helpers.php) can substitute the
	 * autosave's content into that one card server-side.
	 *
	 * @param {string} previewLink Value of core/editor's getEditedPostPreviewLink().
	 * @param {string} anchor      Homepage section id, e.g. "layanan".
	 * @return {string} Empty string if there's nothing to link to yet.
	 */
	function buildSitePreviewUrl( previewLink, anchor ) {
		if ( ! previewLink || ! anchor ) {
			return '';
		}
		var queryIndex = previewLink.indexOf( '?' );
		var queryString = queryIndex !== -1 ? previewLink.slice( queryIndex ) : '';
		return HOME_URL + '#' + anchor + queryString;
	}

	function IconPickerField( props ) {
		var value = props.value;
		var onChange = props.onChange;

		var buttons = Object.keys( ICONS ).map( function ( iconClass ) {
			var isSelected = iconClass === value;
			return el(
				'button',
				{
					type: 'button',
					key: iconClass,
					className: 'dq-icon-picker__option' + ( isSelected ? ' is-selected' : '' ),
					title: ICONS[ iconClass ],
					onClick: function () {
						onChange( iconClass );
					},
				},
				el( 'i', { className: iconClass, 'aria-hidden': 'true' } )
			);
		} );

		return el(
			components.BaseControl,
			{ label: props.label, help: props.help },
			el( components.TextControl, {
				value: value || '',
				onChange: onChange,
			} ),
			el( 'div', { className: 'dq-icon-picker__grid' }, buttons )
		);
	}

	function FieldControl( props ) {
		var field = props.field;
		var value = props.value;
		var onChange = props.onChange;

		if ( 'textarea' === field.type ) {
			return el( components.TextareaControl, {
				label: field.label,
				help: field.help,
				value: value || '',
				rows: 4,
				onChange: onChange,
			} );
		}

		if ( 'checkbox' === field.type ) {
			return el( components.ToggleControl, {
				label: field.label,
				help: field.help,
				checked: !! value,
				onChange: onChange,
			} );
		}

		if ( 'number' === field.type ) {
			return el( components.TextControl, {
				type: 'number',
				min: 1,
				max: 5,
				label: field.label,
				help: field.help,
				value: value || '',
				onChange: onChange,
			} );
		}

		if ( 'url' === field.type ) {
			return el( components.TextControl, {
				type: 'url',
				label: field.label,
				help: field.help,
				value: value || '',
				onChange: onChange,
			} );
		}

		if ( 'icon_picker' === field.type ) {
			return el( IconPickerField, {
				label: field.label,
				help: field.help,
				value: value,
				onChange: onChange,
			} );
		}

		return el( components.TextControl, {
			label: field.label,
			help: field.help,
			value: value || '',
			onChange: onChange,
		} );
	}

	function DetailsPanel() {
		var postType = useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );

		var fields = SCHEMA[ postType ];

		var meta = useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostAttribute( 'meta' ) || {};
		}, [] );

		// Reactive: updates automatically as WordPress' own autosave cycle
		// refreshes the current preview link while the admin keeps editing.
		var previewLink = useSelect( function ( select ) {
			return select( 'core/editor' ).getEditedPostPreviewLink();
		}, [] );

		var editPost = useDispatch( 'core/editor' ).editPost;

		if ( ! fields ) {
			return null;
		}

		function setMeta( key, value ) {
			var next = {};
			next[ key ] = value;
			editPost( { meta: next } );
		}

		var PREVIEW_URL = buildSitePreviewUrl( previewLink, PREVIEW_ANCHORS[ postType ] );

		var children = [];

		if ( PREVIEW_URL ) {
			children.push(
				el(
					'div',
					{ key: 'dq-preview-link', className: 'dq-details-field dq-details-preview' },
					el(
						components.Button,
						{
							variant: 'secondary',
							href: PREVIEW_URL,
							target: '_blank',
							rel: 'noopener noreferrer',
						},
						__( 'Preview di Situs', 'dzurriyyatul-academic' )
					)
				)
			);
		}

		children = children.concat(
			Object.keys( fields ).map( function ( key ) {
				return el(
					'div',
					{ key: key, className: 'dq-details-field' },
					el( FieldControl, {
						field: fields[ key ],
						value: meta[ key ],
						onChange: function ( value ) {
							setMeta( key, value );
						},
					} )
				);
			} )
		);

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'dq-details-panel',
				title: LABELS[ postType ] || 'Detail',
				className: 'dq-details-panel',
			},
			children
		);
	}

	registerPlugin( 'dq-cpt-details-panel', { render: DetailsPanel } );
} )( window.wp );
