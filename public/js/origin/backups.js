$(document).ready(function(){var e=1,a=!1;function t(e,t){a||$("body").find(".data-loader").show(),$.ajax({type:"GET",url:app_route+"?page="+e,dataType:"json",success:function(e){a=!1;var t=["name","date","size","type","download","delete"],n=e.backups.data,o=$("body").find(".list-view"),s="";if(Object.keys(n).length>0)$.each(n,function(e,a){s+='<tr class="clickable_row">                            <td class="text-center">'+(parseInt(e)+1)+"</td>",$.each(t,function(e,t){var n=a[t];"download"==t?s+='<td data-field-name="'+t+'">                                    <a href="'+n+'" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="'+__("Download backup")+'">                                        '+__("Download")+"                                    </a>                                </td>":"delete"==t?s+='<td data-field-name="'+t+'">                                    <button class="btn btn-danger btn-xs delete-backup" data-toggle="tooltip" data-placement="bottom" title="'+__("Delete backup")+'" data-href="'+n+'">                                        '+__("Delete")+"                                    </button>                                </td>":"type"==t?"Database"==n?s+='<td data-field-name="'+t+'">                                        <span class="label label-info">'+__("Database")+"</span>                                    </td>":"Files"==n?s+='<td data-field-name="'+t+'">                                        <span class="label label-warning">'+__("Files")+"</span>                                    </td>":"Database + Files"==n&&(s+='<td data-field-name="'+t+'">                                        <span class="label label-primary">'+__("Database")+" + "+__("Files")+"</span>                                    </td>"):s+='<td data-field-name="'+t+'">'+n+"</td>"}),s+="</tr>"});else{var d=$("body").find(".new-backup").clone().wrap("<div />").parent().html(),i=getAddNewRecord("Backups",d);s='<tr class="no-results">                        <td colspan="'+(t.length+1)+'" class="not-found">'+i+"</td>                    </tr>"}$(o).find(".list-view-items").empty().append(s),Object.keys(n).length>0?($("body").find(".list-header").show(),$("body").find(".list-actions").show(),$("body").find(".item-count").html(e.backups.total||"0"),$("body").find(".item-from").html(e.backups.from||"0"),$("body").find(".item-to").html(e.backups.to||"0"),$("body").find(".origin-pagination-content").empty().append(makePagination(e.backups))):($("body").find(".list-header").hide(),$("body").find(".list-actions").hide()),$("body").find(".data-loader").hide()},error:function(e){if(void 0!==JSON.parse(e.responseText).message)var a=JSON.parse(e.responseText).message;else a=__("Some error occured. Please try again");notify(a,"error"),$("body").find(".data-loader").hide()}})}t(e),$("body").on("click",".refresh-backups",function(){t(e=1)}),$("body").on("click",".delete-backup",function(){var e=$(this).data("href"),a='<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">'+__("No")+'</button>            <button type="button" class="btn btn-danger btn-sm confirm-delete-backup" data-href="'+e+'">'+__("Yes")+"</button>";msgbox(__("Sure you want to delete this backup permanently")+"?",a)}),$("body").on("click",".confirm-delete-backup",function(){var n=$(this).data("href");$.ajax({type:"POST",url:n,dataType:"json",success:function(n){$("body").find("#message-box").modal("hide"),n.success?(a=!0,t(e)):notify(n.msg,"error")},error:function(e){if(void 0!==JSON.parse(e.responseText).message)var a=JSON.parse(e.responseText).message;else a=__("Some error occured. Please try again");notify(a,"error"),$("body").find("#message-box").modal("hide")}})}),$("body").on("click",".create-backup",function(a){a.preventDefault();var n=$(this).data("href");$("body").find(".data-loader-full").show(),$.ajax({type:"POST",url:n,dataType:"json",success:function(a){$("body").find(".data-loader-full").hide(),a.success?(notify(a.msg,"info"),t(e)):notify(a.msg,"error")},error:function(e){if(void 0!==JSON.parse(e.responseText).message)var a=JSON.parse(e.responseText).message;else a=__("Some error occured. Please try again");notify(a,"error"),$("body").find(".data-loader-full").hide()}})}),$("body").on("click",".origin-pagination a",function(a){a.preventDefault(),"#"!=$(this).attr("href")&&$(this).attr("href").indexOf("page=")>=0&&t(e=$(this).attr("href").split("page=")[1])}),$(window).on("hashchange",function(){if(window.location.hash){var e=window.location.hash.replace("#","");if(e==Number.NaN||e<=0)return!1;t(e)}})});
