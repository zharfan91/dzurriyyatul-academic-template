/**
 * Customizer-native repeater UI for the 4 unbounded CPT collections
 * (Layanan, Paket, Mentor, FAQ). Mounts into the empty
 * .dq-cpt-repeater[data-post-type] div each DQ_Customize_CPT_Repeater_Control
 * instance renders (see inc/class-dq-cpt-repeater-control.php), and talks
 * directly to the REST API via wp.apiFetch (already nonce-authenticated by
 * WordPress core) — list, add, edit, delete, reorder — completely
 * independent of the Customizer's own Save/Publish queue. Every write
 * refreshes the preview pane itself via wp.customize.previewer.refresh().
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.element || ! wp.apiFetch || ! wp.customize || ! window.dqCptManager ) {
		return;
	}

	var el = wp.element.createElement;
	var useState = wp.element.useState;
	var useEffect = wp.element.useEffect;
	var renderEl = wp.element.render;
	var apiFetch = wp.apiFetch;
	var components = wp.components;
	var __ = ( wp.i18n && wp.i18n.__ ) || function ( text ) { return text; };

	var CONFIG = window.dqCptManager;
	var TYPES = CONFIG.types || {};
	var ICONS = CONFIG.icons || {};

	function refreshPreview() {
		if ( wp.customize.previewer && typeof wp.customize.previewer.refresh === 'function' ) {
			wp.customize.previewer.refresh();
		}
	}

	function emptyMetaFor( fields ) {
		var meta = {};
		Object.keys( fields ).forEach( function ( key ) {
			meta[ key ] = 'checkbox' === fields[ key ].type ? false : '';
		} );
		return meta;
	}

	function IconPicker( props ) {
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
			'div',
			null,
			el( components.TextControl, { value: value || '', onChange: onChange } ),
			el( 'div', { className: 'dq-icon-picker__grid' }, buttons )
		);
	}

	function FieldInput( props ) {
		var field = props.field;
		var value = props.value;
		var onChange = props.onChange;

		if ( 'textarea' === field.type ) {
			return el( components.TextareaControl, {
				label: field.label,
				help: field.help,
				value: value || '',
				rows: 3,
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
			return el(
				components.BaseControl,
				{ label: field.label, help: field.help },
				el( IconPicker, { value: value, onChange: onChange } )
			);
		}

		return el( components.TextControl, {
			label: field.label,
			help: field.help,
			value: value || '',
			onChange: onChange,
		} );
	}

	function MediaPicker( props ) {
		var value = props.value;
		var previewUrl = props.previewUrl;
		var onChange = props.onChange;

		function openPicker() {
			if ( ! wp.media ) {
				return;
			}
			var frame = wp.media( {
				title: __( 'Pilih Gambar', 'dzurriyyatul-academic' ),
				multiple: false,
				library: { type: 'image' },
			} );
			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				onChange( attachment.id, attachment.url );
			} );
			frame.open();
		}

		return el(
			components.BaseControl,
			{ label: __( 'Foto', 'dzurriyyatul-academic' ) },
			el(
				'div',
				{ className: 'dq-media-picker' },
				previewUrl
					? el( 'img', { src: previewUrl, className: 'dq-media-picker__preview', alt: '' } )
					: null,
				el(
					'div',
					{ className: 'dq-media-picker__actions' },
					el(
						components.Button,
						{ variant: 'secondary', onClick: openPicker },
						value ? __( 'Ganti Gambar', 'dzurriyyatul-academic' ) : __( 'Pilih Gambar', 'dzurriyyatul-academic' )
					),
					value
						? el(
								components.Button,
								{
									variant: 'tertiary',
									isDestructive: true,
									onClick: function () {
										onChange( 0, '' );
									},
								},
								__( 'Hapus', 'dzurriyyatul-academic' )
						  )
						: null
				)
			)
		);
	}

	function ItemEditor( props ) {
		var item = props.item;
		var fields = props.fields;
		var hasThumbnail = props.hasThumbnail;
		var saving = props.saving;
		var onSave = props.onSave;
		var onCancel = props.onCancel;

		var initialTitle = ( item.title && 'object' === typeof item.title )
			? ( item.title.raw || item.title.rendered || '' )
			: ( item.title || '' );

		var initialImageUrl = '';
		if ( item._embedded && item._embedded[ 'wp:featuredmedia' ] && item._embedded[ 'wp:featuredmedia' ][ 0 ] ) {
			initialImageUrl = item._embedded[ 'wp:featuredmedia' ][ 0 ].source_url || '';
		}

		var titleState = useState( initialTitle );
		var title = titleState[ 0 ];
		var setTitle = titleState[ 1 ];

		var metaState = useState( Object.assign( {}, emptyMetaFor( fields ), item.meta || {} ) );
		var meta = metaState[ 0 ];
		var setMeta = metaState[ 1 ];

		var imageState = useState( { id: item.featured_media || 0, url: initialImageUrl } );
		var image = imageState[ 0 ];
		var setImage = imageState[ 1 ];

		function setMetaField( key, value ) {
			var next = Object.assign( {}, meta );
			next[ key ] = value;
			setMeta( next );
		}

		function handleSave() {
			var payload = { title: title, meta: meta };
			if ( hasThumbnail ) {
				payload.featured_media = image.id || 0;
			}
			onSave( payload );
		}

		var fieldNodes = Object.keys( fields ).map( function ( key ) {
			return el(
				'div',
				{ key: key, className: 'dq-cpt-repeater__field' },
				el( FieldInput, {
					field: fields[ key ],
					value: meta[ key ],
					onChange: function ( value ) {
						setMetaField( key, value );
					},
				} )
			);
		} );

		return el(
			'div',
			{ className: 'dq-cpt-repeater__editor' },
			el( components.TextControl, {
				label: __( 'Judul', 'dzurriyyatul-academic' ),
				value: title,
				onChange: setTitle,
			} ),
			hasThumbnail
				? el( MediaPicker, {
						value: image.id,
						previewUrl: image.url,
						onChange: function ( id, url ) {
							setImage( { id: id, url: url } );
						},
				  } )
				: null,
			fieldNodes,
			el(
				'div',
				{ className: 'dq-cpt-repeater__editor-actions' },
				el(
					components.Button,
					{ variant: 'primary', isBusy: saving, disabled: saving, onClick: handleSave },
					__( 'Simpan', 'dzurriyyatul-academic' )
				),
				el(
					components.Button,
					{ variant: 'tertiary', disabled: saving, onClick: onCancel },
					__( 'Batal', 'dzurriyyatul-academic' )
				)
			)
		);
	}

	function RepeaterApp( props ) {
		var postType = props.postType;
		var typeConfig = TYPES[ postType ] || { label: postType, fields: {}, hasThumbnail: false };
		var fields = typeConfig.fields || {};

		var itemsState = useState( null );
		var items = itemsState[ 0 ];
		var setItems = itemsState[ 1 ];

		var editingState = useState( null );
		var editingId = editingState[ 0 ];
		var setEditingId = editingState[ 1 ];

		var savingState = useState( false );
		var saving = savingState[ 0 ];
		var setSaving = savingState[ 1 ];

		var errorState = useState( '' );
		var error = errorState[ 0 ];
		var setError = errorState[ 1 ];

		function loadItems() {
			apiFetch( {
				path: '/wp/v2/' + postType +
					'?per_page=100&orderby=menu_order&order=asc&status=publish,draft&context=edit' +
					'&_embed=wp:featuredmedia&_fields=id,title,status,menu_order,meta,featured_media,_links,_embedded',
			} )
				.then( function ( response ) {
					setItems( response );
				} )
				.catch( function () {
					setError( __( 'Gagal memuat data.', 'dzurriyyatul-academic' ) );
					setItems( [] );
				} );
		}

		useEffect( function () {
			loadItems();
		}, [] );

		function handleSave( payload ) {
			setSaving( true );
			setError( '' );

			var isNew = 'new' === editingId;
			var path = isNew ? '/wp/v2/' + postType : '/wp/v2/' + postType + '/' + editingId;
			var data = Object.assign( {}, payload, { status: 'publish' } );

			apiFetch( { path: path, method: 'POST', data: data } )
				.then( function () {
					setSaving( false );
					setEditingId( null );
					loadItems();
					refreshPreview();
				} )
				.catch( function () {
					setSaving( false );
					setError( __( 'Gagal menyimpan.', 'dzurriyyatul-academic' ) );
				} );
		}

		function handleDelete( id ) {
			if ( ! window.confirm( __( 'Hapus item ini?', 'dzurriyyatul-academic' ) ) ) {
				return;
			}
			apiFetch( { path: '/wp/v2/' + postType + '/' + id, method: 'DELETE' } )
				.then( function () {
					loadItems();
					refreshPreview();
				} )
				.catch( function () {
					setError( __( 'Gagal menghapus.', 'dzurriyyatul-academic' ) );
				} );
		}

		function handleMove( id, direction ) {
			if ( ! items ) {
				return;
			}
			var index = -1;
			items.forEach( function ( it, i ) {
				if ( it.id === id ) {
					index = i;
				}
			} );
			var swapIndex = index + direction;
			if ( -1 === index || swapIndex < 0 || swapIndex >= items.length ) {
				return;
			}
			var a = items[ index ];
			var b = items[ swapIndex ];
			var aNewOrder = b.menu_order;
			var bNewOrder = a.menu_order;

			Promise.all( [
				apiFetch( { path: '/wp/v2/' + postType + '/' + a.id, method: 'POST', data: { menu_order: aNewOrder } } ),
				apiFetch( { path: '/wp/v2/' + postType + '/' + b.id, method: 'POST', data: { menu_order: bNewOrder } } ),
			] )
				.then( function () {
					loadItems();
					refreshPreview();
				} )
				.catch( function () {
					setError( __( 'Gagal mengubah urutan.', 'dzurriyyatul-academic' ) );
				} );
		}

		if ( null === items ) {
			return el( 'p', null, __( 'Memuat…', 'dzurriyyatul-academic' ) );
		}

		var editingItem = null;
		if ( 'new' === editingId ) {
			editingItem = { title: '', meta: {}, featured_media: 0 };
		} else if ( editingId ) {
			items.forEach( function ( it ) {
				if ( it.id === editingId ) {
					editingItem = it;
				}
			} );
		}

		if ( editingItem ) {
			return el(
				'div',
				{ className: 'dq-cpt-repeater__app' },
				error ? el( 'p', { className: 'dq-cpt-repeater__error' }, error ) : null,
				el( ItemEditor, {
					item: editingItem,
					fields: fields,
					hasThumbnail: typeConfig.hasThumbnail,
					saving: saving,
					onSave: handleSave,
					onCancel: function () {
						setEditingId( null );
					},
				} )
			);
		}

		return el(
			'div',
			{ className: 'dq-cpt-repeater__app' },
			error ? el( 'p', { className: 'dq-cpt-repeater__error' }, error ) : null,
			0 === items.length
				? el( 'p', { className: 'dq-cpt-repeater__empty' }, __( 'Belum ada item.', 'dzurriyyatul-academic' ) )
				: el(
						'ul',
						{ className: 'dq-cpt-repeater__list' },
						items.map( function ( item, index ) {
							var titleText = ( item.title && item.title.raw ) || ( item.title && item.title.rendered ) || __( '(Tanpa judul)', 'dzurriyyatul-academic' );
							return el(
								'li',
								{ key: item.id, className: 'dq-cpt-repeater__item' },
								el( 'span', { className: 'dq-cpt-repeater__item-title' }, titleText ),
								'draft' === item.status
									? el( 'span', { className: 'dq-cpt-repeater__item-status' }, __( 'Draf', 'dzurriyyatul-academic' ) )
									: null,
								el(
									'span',
									{ className: 'dq-cpt-repeater__item-actions' },
									el( components.Button, {
										icon: 'arrow-up-alt2',
										label: __( 'Naik', 'dzurriyyatul-academic' ),
										disabled: 0 === index,
										onClick: function () {
											handleMove( item.id, -1 );
										},
									} ),
									el( components.Button, {
										icon: 'arrow-down-alt2',
										label: __( 'Turun', 'dzurriyyatul-academic' ),
										disabled: index === items.length - 1,
										onClick: function () {
											handleMove( item.id, 1 );
										},
									} ),
									el( components.Button, {
										icon: 'edit',
										label: __( 'Edit', 'dzurriyyatul-academic' ),
										onClick: function () {
											setEditingId( item.id );
										},
									} ),
									el( components.Button, {
										icon: 'trash',
										label: __( 'Hapus', 'dzurriyyatul-academic' ),
										isDestructive: true,
										onClick: function () {
											handleDelete( item.id );
										},
									} )
								)
							);
						} )
				  ),
			el(
				components.Button,
				{
					variant: 'primary',
					className: 'dq-cpt-repeater__add',
					onClick: function () {
						setEditingId( 'new' );
					},
				},
				'+ ' + __( 'Tambah Baru', 'dzurriyyatul-academic' )
			)
		);
	}

	function mountAll() {
		var mounts = document.querySelectorAll( '.dq-cpt-repeater[data-post-type]' );
		for ( var i = 0; i < mounts.length; i++ ) {
			var mount = mounts[ i ];
			var postType = mount.getAttribute( 'data-post-type' );
			renderEl( el( RepeaterApp, { postType: postType } ), mount );
		}
	}

	wp.customize.bind( 'ready', mountAll );
} )( window.wp );
