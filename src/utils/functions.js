import { useDebounceFn } from "@vueuse/core";

const fn = {
	fetchAdminAjax: async function ( api_url, method, params = {} ) {
		let requestUrl = api_url;

		const request = {
			method: method.toUpperCase(),
		};

		if ( method.toUpperCase() == "POST" || method.toUpperCase() == "PUT" ) {
			const formData = new FormData();
			this.convertObjectToFormData( formData, params );
			request.body = formData;
		} else if ( method.toUpperCase() == "GET" ) {
			if ( params.hasOwnProperty( 'action' ) ) {
				requestUrl += `?action=${params.action}`;
			}
			Object.keys( params ).forEach( key => {
				if ( key != 'action' && typeof params[ key ] != 'object' ) {
					requestUrl += `&${key}=${params[ key ]}`;
				}
			} );
		}

		const res = await fetch( requestUrl, request );
		const responseText = await res.text();

		try {
			return this.parseJsonResponse( responseText );
		} catch ( error ) {
			console.error( "AccessWise admin AJAX returned invalid JSON.", error, responseText );

			return {
				status: false,
				msg: wp.i18n.__( "The server returned an invalid response. Another plugin may be echoing unexpected output.", "accesswise" ),
				data: [],
			};
		}
	},
	fetchPublicUrl: async function ( api_url, method, params = {} ) {
		let requestUrl = api_url;

		const request = {
			method: method.toUpperCase(),
		};

		if ( method.toUpperCase() == "POST" || method.toUpperCase() == "PUT" ) {
			const formData = new FormData();
			this.convertObjectToFormData( formData, params );
			request.body = formData;
		} else if ( method.toUpperCase() == "GET" ) {
			if ( params.hasOwnProperty( 'action' ) ) {
				requestUrl += `?action=${params.action}`;
			}
			Object.keys( params ).forEach( key => {
				if ( key != 'action' && typeof params[ key ] != 'object' ) {
					requestUrl += `&${key}=${params[ key ]}`;
				}
			} );
		}

		const res = await fetch( requestUrl, request );
		const responseText = await res.text();

		try {
			return this.parseJsonResponse( responseText );
		} catch ( error ) {
			console.error( "AccessWise public request returned invalid JSON.", error, responseText );
			return [];
		}
	},
	parseJsonResponse: function ( responseText ) {
		const normalizedText = String( responseText || "" ).trim().replace( /^\uFEFF/, "" );

		if ( ! normalizedText ) {
			throw new Error( "Empty response." );
		}

		try {
			return JSON.parse( normalizedText );
		} catch ( error ) {
			const extractedJson = this.extractJsonPayload( normalizedText );

			if ( ! extractedJson ) {
				throw error;
			}

			return JSON.parse( extractedJson );
		}
	},
	extractJsonPayload: function ( responseText ) {
		const objectStart = responseText.indexOf( "{" );
		const arrayStart = responseText.indexOf( "[" );
		const startIndexes = [ objectStart, arrayStart ].filter( ( index ) => index !== -1 );

		if ( ! startIndexes.length ) {
			return null;
		}

		const startIndex = Math.min( ...startIndexes );
		const startChar = responseText[ startIndex ];
		const endChar = startChar === "{" ? "}" : "]";
		const endIndex = responseText.lastIndexOf( endChar );

		if ( endIndex === -1 || endIndex <= startIndex ) {
			return null;
		}

		return responseText.slice( startIndex, endIndex + 1 );
	},
	convertObjectToFormData: function ( formData, data, parentKey ) {
		if (
			data &&
			typeof data === "object" &&
			!( data instanceof Date ) &&
			!( data instanceof File )
		) {
			Object.keys( data ).forEach( ( key ) => {
				this.convertObjectToFormData(
					formData,
					data[ key ],
					parentKey ? `${parentKey}[${key}]` : key
				);
			} );
		} else {
			const value = data == null ? "" : data;
			formData.append( parentKey, value );
		}
	},
	setLocalStorage: function ( key, data ) {
		localStorage.setItem( key, data );
	},
	getLocalStorage: function ( key ) {
		return localStorage.getItem( key );
	},
	titleCase ( str ) {
		str = str.replace( /-/g, " " );
		return str
			.toLowerCase()
			.split( " " )
			.map( function ( word ) {
				return word.charAt( 0 ).toUpperCase() + word.slice( 1 );
			} )
			.join( " " );
	},
	useSettingsUpdater ( state ) {
		const updateSetting = async () => {
			const response = await this.fetchAdminAjax( accesswise.admin_ajax, "post", {
				action: "accesswise_update_settings",
				settings: state.settings,
				nonce: accesswise.nonce,
			} );

			if ( response.status ) {
				ElNotification( {
					title: wp.i18n.__( "Success", "accesswise" ),
					message: wp.i18n.__( "Settings have been successfully updated.", "accesswise" ),
					type: "success",
					offset: 50,
				} );

				return response;
			}

			ElNotification( {
				title: wp.i18n.__( "Error", "accesswise" ),
				message: response.msg,
				type: "error",
				offset: 50,
			} );

			return response;
		};

		return {
			updateSetting,
			saveWrittenMessage: useDebounceFn( updateSetting, 1000 ),
		};
	},
};

export default fn;
