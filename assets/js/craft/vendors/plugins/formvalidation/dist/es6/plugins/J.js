import o from"jquery";import t from "../core/Core";const r=o.fn.jquery.split(" ")[0].split(".");if(+r[0]<2&&+r[1]<9||+r[0]===1&&+r[1]===9&&+r[2]<1){throw new Error("The J plugin requires jQuery version 1.9.1 or higher")}o.fn["formValidation"]=function(r){const i=arguments;return this.each((function(){const e=o(this);let n=e.data("formValidation");const a="object"===typeof r&&r;if(!n){n=t(this,a);e.data("formValidation",n).data("FormValidation",n)}if("string"===typeof r){n[r].apply(n,Array.prototype.slice.call(i,1))}}))};