/*! Fabrik */

define(["jquery","fab/fabrik"],function(a,r){return new Class({options:{ajax:!1,controller:"list",parentView:"",defaultStatement:"=",conditionList:"",elementList:"",elementMap:{},statementList:""},initialize:function(t){this.options=a.extend(this.options,t),this.form=a("form.advancedSearch_"+this.options.listref);var e=this.form.find(".advanced-search-add"),i=this.form.find(".advanced-search-clearall"),n=this;0<e.length&&(e.off("click"),e.on("click",function(t){t.preventDefault(),n.addRow()}),i.off("click"),i.on("click",function(t){n.resetForm(t)})),this.form.on("click","tr",function(){n.form.find("tr").removeClass("fabrikRowClick"),a(this).addClass("fabrikRowClick")}),this.watchDelete(),this.watchApply(),this.watchElementList(),r.trigger("fabrik.advancedSearch.ready",this)},watchApply:function(){var n=this;this.form.find(".advanced-search-apply").on("click",function(t){r.fireEvent("fabrik.advancedSearch.submit",n);var e=r["filter_"+n.options.parentView];void 0!==e&&e.onSubmit();var i=n.getList();a(document.createElement("input")).attr({name:"resetfilters",value:1,type:"hidden"}).appendTo(n.form),n.options.ajax&&(t.preventDefault(),i.submit(n.options.controller+".filter"))})},getList:function(){var t=r.blocks["list_"+this.options.listref];return void 0===t&&(t=r.blocks[this.options.parentView]),t},watchDelete:function(){var e=this;this.form.on("click",".advanced-search-remove-row",function(t){t.preventDefault(),e.removeRow(a(this).closest("tr"))})},watchElementList:function(){var n=this;this.form.on("change","select.key",function(t){t.preventDefault();var e=a(this).closest("tr"),i=a(this).val();n.updateValueInput(e,i)})},updateValueInput:function(e,t){var i,n=r.liveSite+"index.php?option=com_fabrik&task=list.elementFilter&format=raw";r.loader.start(e[0]);var o=a(e.find("td")[3]);if(""===t)return o.html(""),void r.loader.stop(e[0]);i=this.options.elementMap[t],a.ajax({url:n,data:{element:t,id:this.options.listid,elid:i.id,plugin:i.plugin,counter:this.options.counter,listref:this.options.listref,context:this.options.controller,parentView:this.options.parentView}}).done(function(t){o.html(t),r.loader.stop(e[0])})},addRow:function(){this.options.counter++;var t=this.form.find(".advanced-search-list").find("tbody").find("tr").last(),e=t.clone();e.removeClass("oddRow1").removeClass("oddRow0").addClass("oddRow"+this.options.counter%2),t.after(e),e.find("td").first().empty().html(this.options.conditionList);var i=e.find("td"),n=a(i[1]);n.empty().html(this.options.elementList),n.append([a(document.createElement("input")).attr({type:"hidden",name:"fabrik___filter[list_"+this.options.listref+"][search_type][]",value:"advanced"}),a(document.createElement("input")).attr({type:"hidden",name:"fabrik___filter[list_"+this.options.listref+"][grouped_to_previous][]",value:"0"})]),a(i[2]).empty().html(this.options.statementList),a(i[3]).empty(),r.trigger("fabrik.advancedSearch.row.added",this)},removeRow:function(t){1<this.form.find(".advanced-search-remove-row").length&&(this.options.counter--,t.animate({height:0,opacity:0},800,function(){t.remove()})),r.trigger("fabrik.advancedSearch.row.removed",this)},resetForm:function(){var t=this.form.find(".advanced-search-list"),e=this;t&&(t.find("tbody tr").each(function(t){1<=t&&a(this).remove(),0===t&&(a(this).find(".inputbox").each(function(){this.id.test(/condition$/)?this.value=e.options.defaultStatement:this.selectedIndex=0}),a(this).find("input").each(function(){a(this).val("")}))}),r.trigger("fabrik.advancedSearch.reset",this))},deleteFilterOption:function(t){var e=this;a(t.target).off("click",function(t){e.deleteFilterOption(t)});var i=a(t.target).parent().parent();i.parent().removeChild(i),t.preventDefault()}})});