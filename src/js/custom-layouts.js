import InlineSVG from 'svg-inline-react'
import he from 'he'

const { useSelect, useDispatch }      = wp.data
const { useState, useEffect }         = wp.element
const { PluginDocumentSettingPanel }  = wp.editPost
const { ToggleControl, RadioControl } = wp.components
const { registerPlugin }              = wp.plugins

const args = { ...enlightenment_custom_layouts_args }

if ( args.bootstrap ) {
    args.last_layout = { ...args.template_layout }
}

const EnlightenmentCustomLayout = () => {
    const {
        meta,
        meta: { _enlightenment_custom_layout },
    } = useSelect( ( select ) => ( {
        meta: select('core/editor').getEditedPostAttribute('meta') || {},
    } ) )

    const { editPost } = useDispatch('core/editor')

    const [ customLayout, setCustomLayout ] = useState( _enlightenment_custom_layout )

	if ( typeof customLayout == 'undefined' ) {
		return null
	}

    const hasCustomLayout = args.bootstrap ? ( Object.keys( customLayout ).length === 0 ? false : true ) : ( customLayout != '' )

    useEffect( () => {
        editPost({
            meta: {
            ...meta,
            _enlightenment_custom_layout: customLayout,
            },
        })
    }, [ customLayout ] )

	if ( Object.keys( customLayout ).length ) {
		args.last_layout = { ...customLayout }
	}

    const toggleCheckbox = args.bootstrap ? (
        <ToggleControl
            label={ he.decode( args.default ) }
            checked={ ! hasCustomLayout }
            onChange={ () => {
				for ( let [ breakpoint, layout ] of Object.entries( args.last_layout ) ) {
					if ( 'inherit' == layout ) {
						continue
					}

					document.body.classList.remove( 'enlightenment-layout' + args.prefixes[ breakpoint ] + '-' + layout )
				}

                if ( hasCustomLayout ) {
                    args.last_layout = { ...customLayout }

                    setCustomLayout( [] )

					for ( let [ breakpoint, layout ] of Object.entries( args.template_layout ) ) {
						if ( 'inherit' == layout ) {
							continue
						}

						document.body.classList.add( 'enlightenment-layout' + args.prefixes[ breakpoint ] + '-' + layout )
					}
                } else {
                    setCustomLayout( args.last_layout )

					for ( let [ breakpoint, layout ] of Object.entries( args.last_layout ) ) {
						if ( 'inherit' == layout ) {
							continue
						}

						document.body.classList.add( 'enlightenment-layout' + args.prefixes[ breakpoint ] + '-' + layout )
					}
                }
            } }
        />
    ) : null

    const RadioLabel = ( props ) => {
        const image = props.image ? ( props.image.search( '<svg ' ) == 0 ? <InlineSVG src={props.image} /> : <img src={props.image} alt={props.label} /> ) : null
        const label = props.image ? <span className="screen-reader-text">{ he.decode( props.label ) }</span> : he.decode( props.label )

        return (
            <span className={ 'enlightenment-' + ( props.image ? 'image' : 'text' ) + '-radio__label' }>
                {image}
                {label}
            </span>
        )
    }

    const radioControls = args.bootstrap ? args.options.map( ( option, index ) => {
        var className = 'enlightenment-image-radio'

        const layouts = option.layouts.map( ( layout, index ) => {
            if ( layout.image ) {
                className += ' enlightenment-image-radio__option-' + index + '-has-image'
            } else {
                className += ' enlightenment-image-radio__option-' + index + '-is-text'
            }

            return ( {
                label: <RadioLabel label={layout.label} image={layout.image} />,
                value: layout.value,
            } )
        } )

        return (
            <RadioControl
				key={ index }
                className={ className }
                label={ he.decode( option.label ) }
                selected={ hasCustomLayout ? customLayout[ option.breakpoint ] : args.last_layout[ option.breakpoint ] }
                options={ layouts }
                onChange={ ( choice ) => {
                    if ( ! hasCustomLayout ) {
                        return
                    }

					document.body.classList.remove( 'enlightenment-layout' + args.prefixes[ option.breakpoint ] + '-' + customLayout[ option.breakpoint ] )

                    var helperLayout = { ...customLayout }

                    helperLayout[ option.breakpoint ] = choice

                    setCustomLayout( helperLayout )

					document.body.classList.add( 'enlightenment-layout' + args.prefixes[ option.breakpoint ] + '-' + helperLayout[ option.breakpoint ] )
                } }
            />
        )
    } ) : ( () => {
        var className = 'enlightenment-image-radio'

        const layouts = [
			{
				label: <RadioLabel label={args.default} image="" />,
				value: '',
			},
			...args.options.map( ( layout, index ) => {
	            if ( layout.image ) {
	                className += ' enlightenment-image-radio__option-' + index + '-has-image'
	            }

	            return ( {
	                label: <RadioLabel label={layout.label} image={layout.image} />,
	                value: layout.value,
	            } )
	        } )
		]

        return (
            <RadioControl
                className={ className }
                selected={ customLayout }
                options={ layouts }
                onChange={ ( choice ) => {
					document.body.classList.remove( 'enlightenment-layout-' + customLayout )

					setCustomLayout( choice )

					if ( choice ) {
						document.body.classList.add( 'enlightenment-layout-' + choice )
					} else {
						document.body.classList.add( 'enlightenment-layout-' + args.template_layout )
					}
				} }
            />
        )
    } )()

    return (
        <PluginDocumentSettingPanel
            name="enlightenment-custom-layout"
            title={ args.panel_title }
            className="enlightenment-custom-layout"
        >
            { toggleCheckbox }

            <div className={ 'enlightenment-custom-layout__radio-controls' + ( args.bootstrap ? ( hasCustomLayout ? '' : ' hidden' ) : '' ) }>
                { radioControls }
            </div>
        </PluginDocumentSettingPanel>
    )
}

registerPlugin( 'enlightenment-custom-layout', {
    render: EnlightenmentCustomLayout,
    icon: '',
} )
