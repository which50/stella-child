require( '../scss/popular.scss' );

(function( ls ) {
	/*
	 * Config
	 */
	var config = {
			clientID : '336706961271-76msfr6tpj2vaqssvi68m0puuscsfeqm.apps.googleusercontent.com',
			scopes   : [ 'https://www.googleapis.com/auth/analytics.readonly' ],
			account  : 'Which-50',
			limit    : 50,
			maxShow  : 5,
			domain   : 'www.which-50.com'
		},
		range = {
			week    : '7daysAgo',
			month   : '30daysAgo',
			allTime : '2012-01-01'
		},
		container = {
			week    : 'most-popular-week',
			month   : 'most-popular-month',
			allTime : 'most-popular-all-time'
		},
		queryDate      = 'most-popular-date',
		queryRange     = range.week,
		queryContainer = container.week;

	/*
	 * Utilities
	 */
	function renderList( html ) {
		document.getElementById( queryContainer ).innerHTML = html;
	}

	function convertTime( timestamp ) {
		var now = new Date( timestamp );

		months = [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];

		return now.getDate() + ' ' + months[ now.getMonth() ] + ', ' + now.getFullYear();
	}

	/*
	 * Main functions
	 */
	function loader() {
		var script = document.createElement( 'script' );

		script.src    = 'https://apis.google.com/js/client.js';
		script.onload = authorise;

		document.head.appendChild( script );
	}

	function bindEvents() {
		document.getElementById('tab-label-1').addEventListener('click', function() {
			queryRange     = range.week;
			queryContainer = container.week;

			authorise();
		});

		document.getElementById('tab-label-2').addEventListener('click', function() {
			queryRange     = range.month;
			queryContainer = container.month;

			authorise();
		});

		document.getElementById('tab-label-3').addEventListener('click', function() {
			queryRange     = range.allTime;
			queryContainer = container.allTime;

			authorise();
		});
	}

	function checkSession() {
		if ( ls && ls.getItem( queryDate ) ) {
			var newDate = new Date(),
				oldDate = new Date( JSON.parse( ls.getItem( queryDate ) ) ),
				diffTime, diffDays; 

			diffTime = newDate.getTime() - oldDate.getTime(); 
			diffDays = diffTime / ( 1000 * 3600 * 24 );

			if ( diffDays >= 7 ) {
				ls.removeItem( queryDate );
				ls.removeItem( container.week );
				ls.removeItem( container.month );
				ls.removeItem( container.allTime );
			}
		}
	}

	function init() {
		checkSession();
		loader();
		bindEvents();
	}

	function authorise() {
		var authData = {
				client_id: config.clientID,
				scope: config.scopes
			};

		var i = setInterval(function() {
			if ( gapi && gapi.auth ) {
				clearInterval( i );

				if ( ls && ls.getItem( queryContainer ) ) {
					console.log( ls.getItem( queryDate ) );
					renderList( JSON.parse( ls.getItem( queryContainer ) ) );
				} else {
					if ( ls ) {
						ls.setItem( queryDate, JSON.stringify( new Date() ) );
					}

					gapi.auth.authorize(authData, function( response ) {
						console.error(response);
						if ( !response.error ) {
							queryAccounts();
						}
					});
				}
			} else {
				authorise();
			}
		}, 500);
	}

	function queryAccounts() {
		gapi.client.load( 'analytics', 'v3' ).then(function() {
			gapi.client.analytics.management.accounts.list().then( handleAccounts );
		});
	}

	function handleAccounts( response ) {
		if ( response.result.items && response.result.items.length ) {
			var items = response.result.items;

			items.forEach(function( v ) {
				if ( v.name && v.name === config.account ) {
					queryProperties( v.id );
				}
			});
		} else {
			console.error( 'No accounts found for this user.' );
		}
	}

	function queryProperties( accountId ) {
		gapi.client.analytics.management.webproperties.list({
			'accountId': accountId
		})
		.then( handleProperties )
		.then(null, function( err ) {
			console.log( err );
		});
	}

	function handleProperties( response ) {
		if (response.result.items && response.result.items.length) {
			var firstAccountId  = response.result.items[0].accountId,
				firstPropertyId = response.result.items[0].id;

			queryProfiles( firstAccountId, firstPropertyId );
		} else {
			console.error( 'No properties found for this user.' );
		}
	}

	function queryProfiles( accountId, propertyId ) {
		gapi.client.analytics.management.profiles.list({
			'accountId'     : accountId,
			'webPropertyId' : propertyId
		})
		.then( handleProfiles )
		.then(null, function( err ) {
			console.log( err );
		});
	}

	function handleProfiles( response ) {
		if ( response.result.items && response.result.items.length ) {
			var firstProfileId = response.result.items[0].id;

			queryCoreReportingApi( firstProfileId );
		} else {
			console.error( 'No views (profiles) found for this user.' );
		}
	}

	function getWPRestAPIResult( posts ) {
		var url     = 'https://' + config.domain + '/wp-json/wp/v2/posts?slug=' + posts,
			xmlhttp = new XMLHttpRequest();

		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
				var html = '';

				results = JSON.parse( xmlhttp.responseText );

				if ( results.length > 0 ) {
					results.forEach(function( v ) {
						html += '<li><a href="https://' + config.domain + '/' + v.slug + '">' + v.title.rendered + '</a><span><time><i class="icon-entypo-clock"></i> ' + convertTime( v.date ) + '</time></span></li>';
					});

					renderList( html );

					if ( ls ) {
						ls.setItem( queryContainer, JSON.stringify( html ) );
					}
				}
			}
		};

		xmlhttp.open( 'GET', url, true );
		xmlhttp.send();
	}

	function queryCoreReportingApi( profileId ) {
		gapi.client.analytics.data.ga.get({
			'ids'        : 'ga:' + profileId,
			'start-date' : queryRange,
			'end-date'   : 'today',
			'metrics'    : 'ga:pageviews',
			'dimensions' : 'ga:pagePath',
			'sort'       : '-ga:pageviews',
			'max-results': config.limit
		})
		.then(function( response ) {
			var results = response.result;
			
			if ( results && results.rows ) {
				var posts = [],
					limit = config.limit,
					rows  = results.rows;

				if ( rows.length > 0 ) {
					for ( var i = 0; i < limit; i++ ) {
						if ( rows[i][0].length > 1 ) {
							posts.push( rows[i][0].split('/')[1] );
						}
					}

					getWPRestAPIResult( posts.toString() );
				}
			}
		})
		.then(null, function( err ) {
			console.log( err );
		});
	}

	/*
	 * Init
	 */
	init();
})( window.localStorage );