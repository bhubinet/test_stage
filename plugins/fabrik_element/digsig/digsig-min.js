/*! Fabrik */

define(["jquery","fab/element"],function(n,i){return window.FbDigsig=new Class({Extends:i,initialize:function(i,e){if(this.setPlugin("digsig"),this.parent(i,e),void 0!==n&&n.noConflict(),!0===this.options.editable){if("null"===typeOf(this.element))return void fconsole("no element found for digsig");var t={defaultAction:"drawIt",lineTop:"100",output:"#"+this.options.sig_id,canvas:"#"+this.element.id+"_oc_pad",drawOnly:!0};n("#"+this.element.id).signaturePad(t).regenerate(this.options.value)}else n("#"+this.options.sig_id).signaturePad({displayOnly:!0}).regenerate(this.options.value)},getValue:function(){return this.options.value},addNewEvent:function(i,e){if("load"===i)return this.loadEvents.push(e),void this.runLoadEvent(e);"change"===i&&(this.changejs=e)}}),window.FbDigsig});