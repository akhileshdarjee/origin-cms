$(document).ready((function(){var i=1;function e(i){var e=function(){var i={},e={},t=$("body").find('[name="owner"]').val(),o=$("body").find('[name="action"]').val(),a=$("body").find('[name="module"]').val();(t||o||a)&&(t&&(e.owner=t),o&&(e.action=o),a&&(e.module=a),i.filters=e);return i}();$("body").find(".data-loader").show(),$.ajax({type:"GET",url:app_route+"?page="+i,data:e,dataType:"json",success:function(i){var e=i.activities.data,t=i.current_user,o=(i.activities.from,"");if($("body").find(".not-found").remove(),e.length>0)$.each(e,(function(i,e){var a=!1,n=t.id==e.user_id?__("You"):e.user;n="<strong>"+__(n)+"</strong>";var d=moment.utc(e.created_at).tz(origin.time_zone).fromNow(),r=moment.utc(e.created_at).tz(origin.time_zone).format("D MMM YYYY • hh:mm A");if("Create"==e.action)var s="indicator-success";else if("Update"==e.action)s="indicator-orange";else if("Delete"==e.action)s="indicator-danger";else if("Auth"==e.module)s="indicator-primary";else s="indicator-purple";if("Auth"==e.module)a="Login"==e.action?n+" "+__("logged in"):n+" "+__("logged out");else{var c="<strong>"+__(e.module)+": "+e.form_title+"</strong>";"Create"==e.action?a=__("New")+" "+c+" "+__("created by")+" "+n:"Update"==e.action?a=c+" "+__("updated by")+" "+n:"Delete"==e.action?(a="<strong>"+__(e.module)+": "+e.form_title+"</strong>",a+=" "+__("deleted by")+" "+n):"Download"==e.action&&(a="Report"==e.module?"<strong>"+e.form_title+"</strong> "+__("was downloaded by")+" "+n:c+" "+__("was downloaded by")+" "+n)}o+='<div>                            <i class="'+e.icon+" timeline-icon "+s+'"></i>                            <div class="timeline-item">                                <span class="time">                                    <i class="fas fa-clock"></i> '+d+'                                </span>                                <div class="timeline-body">'+a+'<br />                                    <small class="text-muted">'+r+"</small>                                </div>                            </div>                        </div>"})),$("body").find(".list-actions").show(),$("body").find(".origin-activities").empty().append(o),$("body").find(".page-no").html(i.activities.current_page||"0"),$("body").find(".item-count").html(i.activities.total||"0"),$("body").find(".item-from").html(i.activities.from||"0"),$("body").find(".item-to").html(i.activities.to||"0"),$("body").find(".origin-pagination-content").empty().append(makePagination(i.activities));else{var a='<div class="not-found">'+getNoResults()+"</div>";$("body").find(".origin-activities").empty(),$("body").find(".origin-activities").after(a),$("body").find(".list-actions").hide()}$("body").find(".data-loader").hide()},error:function(i){if(void 0!==JSON.parse(i.responseText).message)var e=JSON.parse(i.responseText).message;else e=__("Some error occured. Please try again");notify(e,"error"),$("body").find(".data-loader").hide()}})}e(i),$("body").on("click",".refresh-activity",(function(){e(i=1)})),$("body").on("change",".activity-filter",(function(t){e(i=1)})),$("body").on("click",".origin-pagination a",(function(t){t.preventDefault(),"#"!=$(this).attr("href")&&$(this).attr("href").indexOf("page=")>=0&&e(i=$(this).attr("href").split("page=")[1])})),$(window).on("hashchange",(function(){if(window.location.hash){var i=window.location.hash.replace("#","");if(i==Number.NaN||i<=0)return!1;e(i)}}))}));
