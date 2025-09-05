import InlineSVG from 'svg-inline-react'
import he from 'he'

const { useSelect, useDispatch }       = wp.data
const { useState, useEffect }          = wp.element
const { PluginDocumentSettingPanel }   = wp.editPost
const { ToggleControl, SelectControl } = wp.components
const { registerPlugin }               = wp.plugins

const args = { ...enlightenment_unlimited_sidebars_args }

args.last_locations = { ...args.template_locations }

const EnlightenmentSidebarLocations = () => {
    const {
        meta,
        meta: { _enlightenment_sidebar_locations },
    } = useSelect( ( select ) => ( {
        meta: select('core/editor').getEditedPostAttribute('meta') || {},
    } ) )

    const { editPost } = useDispatch('core/editor')

    const [ sidebarLocations, setSidebarLocations ] = useState( _enlightenment_sidebar_locations )

	if ( typeof sidebarLocations == 'undefined' ) {
		return null
	}

    const hasSidebarLocations = ( sidebarLocations != '' )

    useEffect( () => {
        editPost({
            meta: {
            ...meta,
            _enlightenment_sidebar_locations: sidebarLocations,
            },
        })
    }, [ sidebarLocations ] )

    const toggleCheckbox = (
        <ToggleControl
            label={ he.decode( args.default ) }
            checked={ ! hasSidebarLocations }
            onChange={ () => {
                if ( hasSidebarLocations ) {
                    args.last_locations = { ...args.template_locations }
                    setSidebarLocations( [] )
                } else {
                    setSidebarLocations( args.last_locations )
                }
            } }
        />
    )

    const selectControls = args.locations.map( ( location, index ) => {
        return (
            <SelectControl
				key={ index }
				label={ he.decode( location.label ) }
                value={ hasSidebarLocations ? sidebarLocations[ location.name ] : args.last_locations[ location.name ] }
                options={ args.sidebars }
                onChange={ ( choice ) => {
                    if ( ! hasSidebarLocations ) {
                        return
                    }

                    var helperLocations = { ...sidebarLocations }

                    helperLocations[ location.name ] = choice

                    setSidebarLocations( helperLocations )
                } }
            />
        )
    } )

    return (
        <PluginDocumentSettingPanel
            name="enlightenment-sidebar-locations"
            title={ args.panel_title }
            className="enlightenment-sidebar-locations"
        >
            { toggleCheckbox }

            <div className={ 'enlightenment-sidebar-locations__select-controls' + ( hasSidebarLocations ? '' : ' hidden' ) }>
                { selectControls }
            </div>
        </PluginDocumentSettingPanel>
    )
}

registerPlugin('enlightenment-sidebar-locations', {
    render: EnlightenmentSidebarLocations,
    icon: '',
})
