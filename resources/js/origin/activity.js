$(document).ready(function() {
    var currentPage = 1;
    refreshActivity(currentPage);

    $('body').on('click', '.refresh-activity', function() {
        currentPage = 1;
        refreshActivity(currentPage);
    });

    $('body').on('change', '.activity-filter', function (e) {
        currentPage = 1;
        refreshActivity(currentPage);
    });

    // get records when click on pagination links
    $('body').on('click', '.origin-pagination a', function (e) {
        e.preventDefault();

        if ($(this).attr('href') != '#' && $(this).attr('href').indexOf('page=') >= 0) {
            currentPage = $(this).attr('href').split('page=')[1];
            refreshActivity(currentPage);
        }
    });

    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');

            if (page == Number.NaN || page <= 0) {
                return false;
            }
            else {
                refreshActivity(page);
            }
        }
    });

    function refreshActivity(page) {
        var data = getFiltersData();
        $('body').find('.data-loader').show();

        $.ajax({
            type: 'GET',
            url: app_route + '?page=' + page,
            data: data,
            dataType: 'json',
            success: function(data) {
                if (data['success']) {
                    var current_activities = data['data']['activities'];
                    var activities_data = current_activities['data'];
                    var number_start = current_activities['from'];
                    var activities = '';
                    $('body').find('.not-found').remove();

                    if (activities_data.length > 0) {
                        $.each(activities_data, function(index, row) {
                            var desc = false;
                            var user = (data['data']['current_user_id'] == row['user_id']) ? __('You') : row['user'];
                            user = '<strong>' + __(user) + '</strong>';
                            var from_now_time = moment.utc(row['created_at']).tz(origin.time_zone).fromNow();
                            var actual_time = moment.utc(row['created_at']).tz(origin.time_zone).format("D MMM YYYY • hh:mm A");
                            var icon_bg = 'indicator-purple';

                            if (row['action'] == 'Create') {
                                var icon_bg = 'indicator-success';
                            }
                            else if (row['action'] == 'Update') {
                                var icon_bg = 'indicator-orange';
                            }
                            else if (row['action'] == 'Delete') {
                                var icon_bg = 'indicator-danger';
                            }
                            else if (row['module'] == 'Auth') {
                                var icon_bg = 'indicator-primary';
                            }

                            if (row['module'] == 'Auth') {
                                if (row['action'] == 'Login') {
                                    desc = user + ' ' + __('logged in');
                                }
                                else {
                                    desc = user + ' ' + __('logged out');
                                }
                            }
                            else {
                                var activity_link = '<strong>' + __(row["module"]) + ': ' + row["form_title"] + '</strong>';

                                if (row['action'] == 'Create') {
                                    desc = __('New') + ' ' + activity_link + ' ' + __('created by') + ' ' + user;
                                }
                                else if (row['action'] == 'Update') {
                                    desc = activity_link + ' ' + __('updated by') + ' ' + user;
                                }
                                else if (row['action'] == 'Delete') {
                                    desc = '<strong>' + __(row["module"]) + ': ' + row["form_title"] + '</strong>';
                                    desc += ' ' + __('deleted by') + ' ' + user;
                                }
                                else if (row['action'] == 'Download') {
                                    if (row['module'] == 'Report') {
                                        desc = '<strong>' + row["form_title"] + '</strong> ' + __('was downloaded by') + ' ' + user;
                                    }
                                    else {
                                        desc = activity_link + ' ' + __('was downloaded by') + ' ' + user;
                                    }
                                }
                            }

                            activities += '<div>\
                                <i class="' + row["icon"] + ' timeline-icon ' + icon_bg + '"></i>\
                                <div class="timeline-item">\
                                    <span class="time">\
                                        <i class="fas fa-clock"></i> ' + from_now_time + '\
                                    </span>\
                                    <div class="timeline-body">' + desc + '<br />\
                                        <small class="text-muted">' + actual_time + '</small>\
                                    </div>\
                                </div>\
                            </div>';
                        });

                        $('body').find('.list-actions').show();
                        $('body').find('.origin-activities').empty().append(activities);
                        $('body').find('.page-no').html(current_activities['current_page'] || '0');
                        $('body').find('.item-count').html(current_activities['total'] || '0');
                        $('body').find('.item-from').html(current_activities['from'] || '0');
                        $('body').find('.item-to').html(current_activities['to'] || '0');
                        $('body').find('.origin-pagination-content').empty().append(makePagination(current_activities));
                    }
                    else {
                        var no_results = '<div class="not-found">' + getNoResults() + '</div>';

                        $('body').find('.origin-activities').empty();
                        $('body').find('.origin-activities').after(no_results);
                        $('body').find('.list-actions').hide();
                    }
                }

                $('body').find('.data-loader').hide();
            },
            error: function(e) {
                if (typeof JSON.parse(e.responseText)['message'] !== 'undefined') {
                    var error_msg = JSON.parse(e.responseText)['message'];
                }
                else {
                    var error_msg = __('Some error occured. Please try again');
                }

                notify(error_msg, 'error');
                $('body').find('.data-loader').hide();
            }
        });
    }

    // prepare all filters data
    function getFiltersData() {
        var data = {};
        var filters = {};
        var owner = $('body').find('[name="owner"]').val();
        var action = $('body').find('[name="action"]').val();
        var module = $('body').find('[name="module"]').val();

        if (owner || action || module) {
            if (owner) {
                filters['owner'] = owner
            }

            if (action) {
                filters['action'] = action
            }

            if (module) {
                filters['module'] = module
            }

            data['filters'] = filters;
        }

        return data;
    }
});