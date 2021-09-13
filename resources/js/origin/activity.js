$(document).ready(function() {
    var current_page = 1;
    refreshActivity(current_page);

    $("body").on("click", ".refresh-activity", function() {
        current_page = 1;
        refreshActivity(current_page);
    });

    $('body').on('change', '.activity-filter', function (e) {
        current_page = 1;
        refreshActivity(current_page);
    });

    // get records when click on pagination links
    $('body').on('click', '.origin-pagination a', function (e) {
        e.preventDefault();

        if ($(this).attr('href') != "#" && $(this).attr('href').indexOf('page=') >= 0) {
            current_page = $(this).attr('href').split('page=')[1];
            refreshActivity(current_page);
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
        $("body").find(".data-loader").show();

        $.ajax({
            type: 'GET',
            url: app_route + '?page=' + page,
            data: data,
            dataType: 'json',
            success: function(data) {
                var app_activities = data['activities']['data'];
                var current_user = data['current_user'];
                var number_start = data['activities']['from'];
                var activities = "";
                $('body').find('.not-found').remove();

                if (app_activities.length > 0) {
                    $.each(app_activities, function(index, row) {
                        var desc = false;
                        var user = (current_user["id"] == row["user_id"]) ? __('You') : row["user"];
                        user = '<strong>' + __(user) + '</strong>';
                        var from_now_time = moment.utc(row['created_at']).tz(origin.time_zone).fromNow();
                        var actual_time = moment.utc(row['created_at']).tz(origin.time_zone).format("D MMM YYYY • hh:mm A");

                        if (row['action'] == "Create") {
                            var icon_bg = "indicator-success"
                        }
                        else if (row['action'] == "Update") {
                            var icon_bg = "indicator-orange"
                        }
                        else if (row['action'] == "Delete") {
                            var icon_bg = "indicator-danger"
                        }
                        else if (row["module"] == "Auth") {
                            var icon_bg = "indicator-primary"
                        }
                        else {
                            var icon_bg = "indicator-purple"
                        }

                        if (row["module"] == "Auth") {
                            if (row["action"] == "Login") {
                                desc = user + " " + __('logged in');
                            }
                            else {
                                desc = user + " " + __('logged out');
                            }
                        }
                        else {
                            var activity_link = '<strong>' + __(row["module"]) + ': ' + row["form_title"] + '</strong>';

                            if (row["action"] == "Create") {
                                desc = __('New') + " " + activity_link + " " + __('created by') + " " + user;
                            }
                            else if (row["action"] == "Update") {
                                desc = activity_link + " " + __('updated by') + " " + user;
                            }
                            else if (row["action"] == "Delete") {
                                desc = '<strong>' + __(row["module"]) + ': ' + row["form_title"] + '</strong>';
                                desc += ' ' + __('deleted by') + ' ' + user;
                            }
                            else if (row["action"] == "Download") {
                                if (row["module"] == "Report") {
                                    desc = '<strong>' + row["form_title"] + '</strong> ' + __('was downloaded by') + ' ' + user;
                                }
                                else {
                                    desc = activity_link + " " + __('was downloaded by') + " " + user;
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

                    $("body").find(".list-actions").show();
                    $('body').find(".origin-activities").empty().append(activities);
                    $("body").find(".page-no").html(data['activities']['current_page'] || '0');
                    $("body").find(".item-count").html(data['activities']['total'] || '0');
                    $("body").find(".item-from").html(data['activities']['from'] || '0');
                    $("body").find(".item-to").html(data['activities']['to'] || '0');
                    $("body").find(".origin-pagination-content").empty().append(makePagination(data['activities']));
                }
                else {
                    var no_results = '<div class="not-found">' + getNoResults() + '</div>';

                    $("body").find(".origin-activities").empty();
                    $("body").find(".origin-activities").after(no_results);
                    $("body").find(".list-actions").hide();
                }

                $("body").find(".data-loader").hide();
            },
            error: function(e) {
                if (typeof JSON.parse(e.responseText)['message'] !== 'undefined') {
                    var error_msg = JSON.parse(e.responseText)['message'];
                }
                else {
                    var error_msg = __('Some error occured. Please try again');
                }

                notify(error_msg, "error");
                $("body").find(".data-loader").hide();
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