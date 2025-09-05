jQuery(document).ready(function($) {
	var $document  = $(document);

	/*if( typeof $.fn.infinitescroll != 'undefined' ) {
		$('#activity-stream').infinitescroll({
			contentSelector : '#activity-stream',
			navSelector     : '.activity-list .load-more',
			nextSelector    : '.activity-list .load-more a',
			itemSelector    : '.activity-list .activity-item',
			loading         : {
				msgText     : '<i class="fa fa-spinner fa-pulse"></i>',
				finishedMsg : 'There are no more posts to display.',
			},
		}, function(response) {
			var items = $(response);

			items.each(function() {
				enlightenment_bp_hide_comments(this);
			});

			$document.trigger('enlightenment_bp_activity_get_older_updates', [ items ]);
		});
	}*/
	/*var $window = $(window),
		load_more = $('#buddypress .load-more'),
		offsetTop = load_more.length ? load_more.offset().top - 50 : 0,
		scrollPos = 0,
		ticking   = false;

	if( load_more.length ) {
		$window.on('scroll', infscr);
		$document.on('enlightenment_bp_activity_get_older_updates', infscr_update);
	}
	function infscr() {
		scrollPos = window.scrollY + window.innerHeight;
		if( ! ticking ) {
			requestAnimationFrame( infscr_anim );
			ticking = true;
		}
	}
	function infscr_anim() {
		ticking = false;
		if( scrollPos > offsetTop ) {
			$window.off('scroll', infscr);
			load_more.find('a').trigger('click');
		}
	}
	function infscr_update() {
		load_more = $('#buddypress .load-more:last-child');
		if( load_more.length ) {
			offsetTop = load_more.offset().top - 50;
			$window.on('scroll', infscr);
		}
	}*/

	/* Stream event delegation */
	$('div.activity').on( 'click', '.activity-meta .bp-secondary-action .fa', function(event) {
		var target = $(event.target).parent(),
			type, parent, parent_id,
			li, id, link_href, nonce, timestamp;

		/* Favoriting activity stream items */
		if ( target.hasClass('fav') || target.hasClass('unfav') ) {
			type      = target.hasClass('fav') ? 'fav' : 'unfav';
			parent    = target.closest('.activity-item');
			parent_id = parent.attr('id').substr( 9, parent.attr('id').length );

			target.addClass('loading');

			$.post( ajaxurl, {
				action: 'activity_mark_' + type,
				'cookie': bp_get_cookies(),
				'id': parent_id
			},
			function(response) {
				target.removeClass('loading');

				target.fadeOut( 200, function() {
					$(this).html(response);
					$(this).attr('title', 'fav' === type ? BP_DTheme.remove_fav : BP_DTheme.mark_as_fav);
					$(this).fadeIn(200);
				});

				if ( 'fav' === type ) {
					if ( !$('.item-list-tabs #activity-favs-personal-li').length ) {
						if ( !$('.item-list-tabs #activity-favorites').length ) {
							$('.item-list-tabs ul #activity-mentions').before( '<li id="activity-favorites"><a href="#">' + BP_DTheme.my_favs + ' <span>0</span></a></li>');
						}

						$('.item-list-tabs ul #activity-favorites span').html( Number( $('.item-list-tabs ul #activity-favorites span').html() ) + 1 );
					}

					target.removeClass('fav');
					target.addClass('unfav');

				} else {
					target.removeClass('unfav');
					target.addClass('fav');

					$('.item-list-tabs ul #activity-favorites span').html( Number( $('.item-list-tabs ul #activity-favorites span').html() ) - 1 );

					if ( !Number( $('.item-list-tabs ul #activity-favorites span').html() ) ) {
						if ( $('.item-list-tabs ul #activity-favorites').hasClass('selected') ) {
							bp_activity_request( null, null );
						}

						$('.item-list-tabs ul #activity-favorites').remove();
					}
				}

				if ( 'activity-favorites' === $( '.item-list-tabs li.selected').attr('id') ) {
					target.closest( '.activity-item' ).slideUp( 100 );
				}
			});

			return false;
		}

		/* Delete activity stream items */
		if ( target.hasClass('delete-activity') ) {
			li        = target.parents('div.activity ul li');
			id        = li.attr('id').substr( 9, li.attr('id').length );
			link_href = target.attr('href');
			nonce     = link_href.split('_wpnonce=');
			timestamp = li.prop( 'class' ).match( /date-recorded-([0-9]+)/ );
			nonce     = nonce[1];

			target.addClass('loading');

			$.post( ajaxurl, {
				action: 'delete_activity',
				'cookie': bp_get_cookies(),
				'id': id,
				'_wpnonce': nonce
			},
			function(response) {

				if ( response[0] + response[1] === '-1' ) {
					li.prepend( response.substr( 2, response.length ) );
					li.children('#message').hide().fadeIn(300);
				} else {
					li.slideUp(300);

					// reset vars to get newest activities
					if ( timestamp && activity_last_recorded === timestamp[1] ) {
						newest_activities = '';
						activity_last_recorded  = 0;
					}
				}
			});

			return false;
		}
	});

	if( typeof newest_activities != 'undefined' ) {
		$('div.activity').on( 'click', function(event) {
			var target = $(event.target).parent();

			if( target.hasClass('load-newest') && newest_activities == '' ) {
				var items = target.prevAll('.activity-item');

				items.each(function() {
					enlightenment_bp_hide_comments(this);
				});

				$document.trigger('enlightenment_bp_activity_load_newest_updates', [ items ]);
			}
		});
	}

	$.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
		// Modify options, control originalOptions, store jqXHR, etc
		if( typeof originalOptions.data == 'undefined' ) {
			return true;
		}

		if( typeof originalOptions.data.action == 'undefined' ) {
			return true;
		}

		var optsuccess  = options.success,
			origsuccess = originalOptions.success,
			prevCallback = function(response) {
				if(optsuccess.toString() != origsuccess.toString()) {
					optsuccess(response);
				} else {
					origsuccess(response);
				}
			};

		if( originalOptions.data.action == 'post_update' ) {
			options.success = function(response) {
				prevCallback(response);

				if( response[0] + response[1] === '-1' ) {
					return true;
				}

				var item = $('#activity-stream li').first('.just-posted');
				$document.trigger('enlightenment_bp_post_update', [ item ]);
			}
		} else if( originalOptions.data.action == 'activity_mark_fav' ) {
			options.success = function(response) {
				prevCallback(response);

				var button = $('#activity-' + originalOptions.id).find('.fav');

				$document.trigger('enlightenment_bp_activity_mark_fav', [ button, originalOptions.id ]);
			}
		} else if( originalOptions.data.action == 'activity_mark_unfav' ) {
			options.success = function(response) {
				prevCallback(response);

				var button = $('#activity-' + originalOptions.id).find('.unfav');

				$document.trigger('enlightenment_bp_activity_mark_unfav', [ button, originalOptions.id ]);
			}
		} else if( originalOptions.data.action == 'delete_activity' ) {
			options.success = function(response) {
				prevCallback(response);
				$document.trigger('enlightenment_bp_delete_activity', [ originalOptions.id ]);
			}
		} else if( originalOptions.data.action == 'bp_spam_activity' ) {
			options.success = function(response) {
				prevCallback(response);
				$document.trigger('enlightenment_bp_spam_activity', [ originalOptions.id ]);
			}
		} else if( originalOptions.data.action == 'activity_get_older_updates' ) {
			options.success = function(response) {
				prevCallback(response);

				var items = $('.load-more[style]').last().nextAll('.activity-item');

				items.each(function() {
					enlightenment_bp_hide_comments(this);
				});

				$document.trigger('enlightenment_bp_activity_get_older_updates', [ items ]);
			}
		} /*else if( originalOptions.data.action == 'get_single_activity_content' ) {
			options.success = function(response) {
				prevCallback(response);

				var type        = .parent().attr('id').split('-')[0],
					inner_class = type === 'acomment' ? 'acomment-content' : 'activity-inner',
					a_inner     = $('#' + type + '-' + originalOptions.activity_id + ' .' + inner_class + ':first' );

				$document.trigger('enlightenment_bp_get_single_activity_content', [ response, originalOptions.activity_id ]);
			}
		}*/ else if( originalOptions.data.action == 'new_activity_comment' ) {
			options.success = function(response) {
				prevCallback(response);

				if( response[0] + response[1] === '-1' ) {
					return true;
				}

				var comment = $('#ac-form-' + form_id).parent().children('ul').children('li').first();

				$document.trigger('enlightenment_bp_new_activity_comment', [ comment, originalOptions.comment_id, originalOptions.form_id, originalOptions.content ]);
			}
		} else if( originalOptions.data.action == 'delete_activity_comment' ) {
			options.success = function(response) {
				prevCallback(response);

				if( response[0] + response[1] === '-1' ) {
					return true;
				}

				$document.trigger('enlightenment_bp_delete_activity_comment', [ originalOptions.id ]);
			}
		} else if( originalOptions.data.action == 'bp_spam_activity_comment' ) {
			options.success = function(response) {
				prevCallback(response);

				if( response[0] + response[1] === '-1' ) {
					return true;
				}

				$document.trigger('enlightenment_bp_spam_activity_comment', [ originalOptions.id ]);
			}
		} else if( originalOptions.data.action == 'groups_invite_user' ) {
			options.success = function(response) {
				prevCallback(response);
				$document.trigger('enlightenment_bp_groups_invite_user', [ response, originalOptions.friend_action, originalOptions.friend_id, originalOptions.group_id ]);
			}
		} else if( originalOptions.data.action == 'accept_friendship' ) {
			options.success = function(response) {
				prevCallback(response);

				if( response[0] + response[1] === '-1' ) {
					return true;
				}

				$document.trigger('enlightenment_bp_accept_friendship', [ originalOptions.id ]);
			}
		} else if( originalOptions.data.action == 'reject_friendship' ) {
			options.success = function(response) {
				prevCallback(response);

				if( response[0] + response[1] === '-1' ) {
					return true;
				}

				$document.trigger('enlightenment_bp_reject_friendship', [ originalOptions.id ]);
			}
		} /*else if( originalOptions.data.action == 'addremove_friend' ) {
			options.success = function(response) {
				prevCallback(response);
				$document.trigger('enlightenment_bp_addremove_friend', [ response, originalOptions.fid ]);
			}
		} else if( originalOptions.data.action == 'joinleave_group' ) {
			options.success = function(response) {
				prevCallback(response);
				$document.trigger('enlightenment_bp_joinleave_group', [ response, originalOptions.gid ]);
			}
		}*/ else if( originalOptions.data.action == 'messages_send_reply' ) {
			options.success = function(response) {
				prevCallback(response);

				if( response[0] + response[1] === '-1' ) {
					return true;
				}

				$document.trigger('enlightenment_bp_messages_send_reply', [ response, originalOptions.content, originalOptions.send_to, originalOptions.subject, originalOptions.thread_id ]);
			}
		} else if( originalOptions.data.action == 'messages_delete' ) {
			options.success = function(response) {
				prevCallback(response);

				if( response[0] + response[1] === '-1' ) {
					return true;
				}

				$document.trigger('enlightenment_bp_messages_delete', [ response, originalOptions.thread_ids ]);
			}
		} else if( originalOptions.data.action == 'messages_star' ) {
			options.success = function(response) {
				prevCallback(response);
				$document.trigger('enlightenment_bp_messages_star', [ response, originalOptions.message_id, originalOptions.star_status, originalOptions.bulk ]);
			}
		} else if( originalOptions.data.action == 'messages_close_notice' ) {
			options.success = function(response) {
				prevCallback(response);

				if( response[0] + response[1] === '-1' ) {
					return true;
				}

				$document.trigger('enlightenment_bp_messages_close_notice', [ response, originalOptions.notice_id ]);
			}
		} else if( originalOptions.data.action == 'activity_widget_filter' ) {
			options.success = function(response) {
				prevCallback(response);
				$document.trigger('enlightenment_bp_activity_widget_filter', [ response, originalOptions.scope, originalOptions.filter ]);
			}
		}
	});

	/** Live Notifications HeartBeat ************************************************/

	// Set the last id to request after
	var newest_activities = '',
		notifications_last_recorded  = {
			friends  : 0,
			messages : 0,
			other    : 0,
		},
		first_notifications_recorded = {
			friends  : 0,
			messages : 0,
			other    : 0,
		};

	$document.on( 'heartbeat-send', function(event, data) {
		data = data || {};
		first_notifications_recorded = {
			friends  : 0,
			messages : 0,
			other    : 0,
		};

		// First row is default latest notification
		var first_friends  = $( '#bp-friends-notifications .notification' ).first(),
			first_messages = $( '#bp-messages-notifications .notification' ).first(),
			first_other    = $( '#bp-other-notifications .notification' ).first();

		if ( first_friends.prop( 'id' ) ) {
			// getting the timestamp
			timestamp = first_friends.find('.time-since').data('date-notified');

			if ( timestamp ) {
				first_notifications_recorded.friends = timestamp;

				if ( 0 === notifications_last_recorded.friends || Number( first_notifications_recorded.friends ) > notifications_last_recorded.friends ) {
					notifications_last_recorded.friends = Number( first_notifications_recorded.friends );
				}
			}
		}

		if ( first_messages.prop( 'id' ) ) {
			// getting the timestamp
			timestamp = first_messages.find('.time-since').data('date-notified');

			if ( timestamp ) {
				first_notifications_recorded.messages = timestamp;

				if ( 0 === notifications_last_recorded.messages || Number( first_notifications_recorded.messages ) > notifications_last_recorded.messages ) {
					notifications_last_recorded.messages = Number( first_notifications_recorded.messages );
				}
			}
		}

		if ( first_other.prop( 'id' ) ) {
			// getting the timestamp
			timestamp = first_other.find('.time-since').data('date-notified');

			if ( timestamp ) {
				first_notifications_recorded.other = timestamp;

				if ( 0 === notifications_last_recorded.other || Number( first_notifications_recorded.other ) > notifications_last_recorded.other ) {
					notifications_last_recorded.other = Number( first_notifications_recorded.other );
				}
			}
		}

		/*$.each(notifications_last_recorded, function(key, value) {
			if ( 0 === value || Number( first_notifications_recorded[key] ) > value ) {
				notifications_last_recorded[key] = Number( first_notifications_recorded[key] );
			}
		});*/

		data.bp_notifications_last_recorded = notifications_last_recorded;

		if ( typeof data.bp_ajax_messages_get_thread != 'undefined' ) {
			var thread_id = data.bp_ajax_messages_get_thread.thread_id,
				$list     = $('#bp-messages-notifications .notifications-list'),
				$count    = $('#bp-messages-notifications .dropdown-toggle .count'),
				$thread   = $list.children('#message-thread-notification-' + thread_id);
				count     = Number( $count.text() );

			if( $thread.length && $thread.hasClass('unread') ) {
				count--;
				$count.text(count);
				if( count == 0 ) {
					$count.addClass( 'no-alert' );
				}

				$thread.removeClass('unread');
				$thread.find('.unread-count').remove();
			}
		}
	});

	// Push new notifications if data has been returned
	$document.on( 'heartbeat-tick', function( event, data ) {
		// Only proceed if we have newest notifications
		if ( typeof data.bp_notifications_newest_notifications != 'undefined' ) {
			var newest_notifications          = data.bp_notifications_newest_notifications.all,
				newest_friends_notifications  = data.bp_notifications_newest_notifications.friends,
				newest_messages_notifications = data.bp_notifications_newest_notifications.messages,
				newest_other_notifications    = data.bp_notifications_newest_notifications.other;

			if( typeof newest_friends_notifications.last_recorded != 'undefined' ) {
				notifications_last_recorded.friends = Number( newest_friends_notifications.last_recorded );

				var $list   = $('#bp-friends-notifications .notifications-list'),
					$count  = $('#bp-friends-notifications .dropdown-toggle .count'),
					$notice = $list.children('.no-notifications'),
					$contents = $(newest_friends_notifications.contents);

				$count.removeClass( 'no-alert' );
				$count.addClass('alert');
				$count.text( Number( $count.text() ) + $contents.length );

				if( $notice.length ) {
					$notice.remove();
				}

				$list.prepend($contents);
			}

			if( typeof newest_messages_notifications.last_recorded != 'undefined' ) {
				notifications_last_recorded.messages = Number( newest_messages_notifications.last_recorded );

				var $list     = $('#bp-messages-notifications .notifications-list'),
					$count    = $('#bp-messages-notifications .dropdown-toggle .count'),
					$notice   = $list.children('.no-notifications'),
					$contents = $(newest_messages_notifications.contents),
					count     = Number( $count.text() );

				$count.removeClass( 'no-alert' );
				$count.addClass('alert');

				if( $notice.length ) {
					$notice.remove();
				}

				$.each($contents, function(key, notification) {
					var $notification = $(notification),
						$thread       = $list.children('#' + $notification.attr('id'));

					if( $thread.length ) {
						if( ! $thread.hasClass('unread') ) {
							$thread.addClass('unread');

							count++;
						}

						$thread.html($notification.html());
					} else {
						$list.prepend($thread);
					}
				});

				$count.text( count );
			}

			if( typeof newest_other_notifications.last_recorded != 'undefined' ) {
				notifications_last_recorded.other = Number( newest_other_notifications.last_recorded );

				var $list     = $('#bp-other-notifications .notifications-list'),
					$count    = $('#bp-other-notifications .dropdown-toggle .count'),
					$notice   = $list.children('.no-notifications'),
					$contents = $(newest_other_notifications.contents);

				$count.removeClass( 'no-alert' );
				$count.addClass('alert');
				$count.text( Number( $count.text() ) + $contents.length );

				if( $notice.length ) {
					$notice.remove();
				}

				$list.prepend($contents);
			}

			if( newest_notifications.length ) {
				var $notifications = $('#live-notifications'),
					i = 0;

				if( ! $notifications.length ) {
					var $sitewidenotice = $('#sitewide-notice');

					$notifications = $('<ul id="live-notifications"></ul>');

					if( $sitewidenotice.length ) {
						$sitewidenotice.after($notifications);
					} else {
						$('body').append($notifications);
					}
				}

				function _iterate() {
					if( typeof bp_ajax_messages_args != 'undefined' && newest_notifications[i].component == 'messages' ) {
						i++;

						if( i < newest_notifications.length ) {
							_iterate();
						}

						return;
					}

					var item = $(newest_notifications[i].content);

					item.on('mouseenter', function() {
						var $this = $(this);

						$this.stop(true).removeClass('collapsing').removeAttr('style');

						setTimeout(function() {
							if( $this.is(':hover') ) {
								return false;
							}

							$this.addClass('collapsing').fadeOut(1000, function() {
								$(this).remove();
							});
						}, 15000);
					});

					item.on('mouseleave', function() {
						var $this = $(this);

						setTimeout(function() {
							$this.addClass('collapsing').fadeOut(1000, function() {
								$(this).remove();
							});
						}, 15000);
					});

					item.addClass('loading');

					$notifications.append(item);

					if( $notifications.children().length > 5 ) {
						$notifications.children().first().addClass('collapsing').fadeOut(250, function() {
							$(this).remove();
						});
					}

					setTimeout(function() {
						item.removeClass('loading');
					}, 100);

					setTimeout(function() {
						item.addClass('collapsing').fadeOut(1000, function() {
							$(this).remove();
						});
					}, 15000);

					i++;

					if( i < newest_notifications.length ) {
						setTimeout(_iterate, 5000);
					}
				}
				_iterate();
			}
		}

		/*if ( typeof data.bp_ajax_messages_get_thread != 'undefined' ) {
			var response  = data.bp_ajax_messages_get_thread,
				$list     = $('#bp-messages-notifications .notifications-list'),
				$count    = $('#bp-messages-notifications .dropdown-toggle .count'),
				$thread   = $list.children('#message-thread-notification-' + response.thread_id);
				count     = Number( $count.text() );

			if( $thread.length && $thread.hasClass('unread') ) {
				count--;
				$count.text(count);
				if( count == 0 ) {
					$count.addClass( 'no-alert' );
				}

				$thread.removeClass('unread');
				$thread.find('.unread-count').remove();
			}
		}*/

		if( typeof data.bp_ajax_thread_markread != 'undefined' ) {
			var response  = data.bp_ajax_thread_markread,
				$list     = $('#bp-messages-notifications .notifications-list'),
				$count    = $('#bp-messages-notifications .dropdown-toggle .count'),
				$thread   = $list.children('#message-thread-notification-' + response.thread_id);
				count     = Number( $count.text() );

			if( $thread.length && $thread.hasClass('unread') ) {
				count--;
				$count.text(count);
				if( count == 0 ) {
					$count.addClass( 'no-alert' );
				}

				$thread.removeClass('unread');
				$thread.find('.unread-count').remove();
			}
		}

		if( typeof data.bp_ajax_messages_send_reply != 'undefined' ) {
			var response  = data.bp_ajax_messages_send_reply,
				$list     = $('#bp-messages-notifications .notifications-list'),
				$count    = $('#bp-messages-notifications .dropdown-toggle .count'),
				$thread   = $list.children('#message-thread-notification-' + response.thread_id);
				count     = Number( $count.text() );
				content   = ('&ldquo;%s&rdquo;').replace('%s', $(response.message).text());

			$thread.find('.status-icon').html( '<i class="fa fa-reply"></i>' );
			$thread.find('.last-message-content').html( $(response.message).text() );

			if( $thread.length && $thread.hasClass('unread') ) {
				count--;
				$count.text(count);
				if( count == 0 ) {
					$count.addClass( 'no-alert' );
				}

				$thread.removeClass('unread');
				$thread.find('.unread-count').remove();
			}
		}
	});

	$document.on('bp_ajax_thread_markread', function(event, thread_id) {
		var $list     = $('#bp-messages-notifications .notifications-list'),
			$count    = $('#bp-messages-notifications .dropdown-toggle .count'),
			$thread   = $list.children('#message-thread-notification-' + thread_id);
			count     = Number( $count.text() );

		if( $thread.length && $thread.hasClass('unread') ) {
			count--;
			$count.text(count);
			if( count == 0 ) {
				$count.addClass( 'no-alert' );
			}

			$thread.removeClass('unread');
			$thread.find('.unread-count').remove();
		}
	});

	$document.on( 'we_debug_heartbeat', function( event ) {
		var newest_notifications = ['<li class="notification dropdown-item" id="notification-215"><img src="//www.gravatar.com/avatar/3bc6b34d80fd10ebfa405d8e4df90cb4?s=48&amp;r=g&amp;d=mm" class="avatar user-9-avatar avatar-48 photo" width="48" height="48" alt="Profile Photo"> <a href="http://srv.local/we/members/alexandra/" title="Alexandra Ellis">Alexandra Ellis</a> likes your status<a class="time-since" href="http://srv.local/we/activity/p/267/" data-date-notified="1473055704">1 minute ago</a></li>'];

		if( newest_notifications.length ) {
			newest_notifications.sort(function(a, b) {
				var atimestamp = Number( $(a).children('.time-since').data('1472987212') ),
					btimestamp = Number( $(b).children('.time-since').data('1472987212') );

				return atimestamp - btimestamp;
			});

			var $notifications = $('#live-notifications'),
				i = 0;

			if( ! $notifications.length ) {
				var $sitewidenotice = $('#sitewide-notice');

				$notifications = $('<ul id="live-notifications"></ul>');

				if( $sitewidenotice.length ) {
					$sitewidenotice.after($notifications);
				} else {
					$('body').append($notifications);
				}
			}

			function _iterate() {
				var item = $(newest_notifications[i]);

				item.on('mouseenter', function() {
					var $this = $(this);

					$this.stop(true).removeClass('collapsing').removeAttr('style');

					setTimeout(function() {
						if( $this.is(':hover') ) {
							return false;
						}

						$this.addClass('collapsing').fadeOut(1000, function() {
							$(this).remove();
						});
					}, 15000);
				});

				item.on('mouseleave', function() {
					var $this = $(this);

					setTimeout(function() {
						$this.addClass('collapsing').fadeOut(1000, function() {
							$(this).remove();
						});
					}, 15000);
				});

				item.addClass('loading');

				$notifications.append(item);

				if( $notifications.children().length > 5 ) {
					$notifications.children().first().addClass('collapsing').fadeOut(250, function() {
						$(this).remove();
					});
				}

				setTimeout(function() {
					item.removeClass('loading');
				}, 100);

				setTimeout(function() {
					item.addClass('collapsing').fadeOut(1000, function() {
						$(this).remove();
					});
				}, 15000);

				i++;

				if( i < newest_notifications.length ) {
					setTimeout(_iterate, 5000);
				}
			}
			_iterate();
		}
	});
});

function enlightenment_bp_hide_comments(item) {
	var $             = jQuery,
		activity      = $(item),
		comments      = activity.children('.activity-comments').children('ul').children('li'),
		comment_count = ' ';

	if( ! comments.length || comments.length < 5 ) {
		return false;
	}

	if( $('#' + activity.attr('id') + ' a.acomment-reply span').length ) {
		comment_count = $('#' + activity.attr('id') + ' a.acomment-reply span').html();
	}

	comments.each(function(i) {
		comment = $(this);

		/* Show the latest 5 root comments */
		if ( i < comments.length - 5 ) {
			comment.addClass('hidden');
			comment.toggle();

			if ( ! i ) {
				comment.before( '<li class="show-all"><a href="#' + activity.attr('id') + '/show-all/" title="' + BP_DTheme.show_all_comments + '">' + BP_DTheme.show_x_comments.replace( '%d', comment_count ) + '</a></li>' );
			}
		}
	});
}
