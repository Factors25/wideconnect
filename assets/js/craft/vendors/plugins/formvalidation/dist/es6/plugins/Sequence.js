import e from "../core/Plugin";export default class i extends e{constructor(e){super(e);this.invalidFields=new Map;this.opts=Object.assign({},{enabled:true},e);this.validatorHandler=this.onValidatorValidated.bind(this);this.shouldValidateFilter=this.shouldValidate.bind(this);this.fieldAddedHandler=this.onFieldAdded.bind(this);this.elementNotValidatedHandler=this.onElementNotValidated.bind(this);this.elementValidatingHandler=this.onElementValidating.bind(this)}install(){this.core.on("core.validator.validated",this.validatorHandler).on("core.field.added",this.fieldAddedHandler).on("core.element.notvalidated",this.elementNotValidatedHandler).on("core.element.validating",this.elementValidatingHandler).registerFilter("field-should-validate",this.shouldValidateFilter)}uninstall(){this.invalidFields.clear();this.core.off("core.validator.validated",this.validatorHandler).off("core.field.added",this.fieldAddedHandler).off("core.element.notvalidated",this.elementNotValidatedHandler).off("core.element.validating",this.elementValidatingHandler).deregisterFilter("field-should-validate",this.shouldValidateFilter)}shouldValidate(e, i, t, l){const d=(this.opts.enabled===true||this.opts.enabled[e]===true)&&this.invalidFields.has(i)&&!!this.invalidFields.get(i).length&&this.invalidFields.get(i).indexOf(l)===-1;return!d}onValidatorValidated(e){const i=this.invalidFields.has(e.element)?this.invalidFields.get(e.element):[];const t=i.indexOf(e.validator);if(e.result.valid&&t>=0){i.splice(t,1)}else if(!e.result.valid&&t===-1){i.push(e.validator)}this.invalidFields.set(e.element,i)}onFieldAdded(e){if(e.elements){this.clearInvalidFields(e.elements)}}onElementNotValidated(e){this.clearInvalidFields(e.elements)}onElementValidating(e){this.clearInvalidFields(e.elements)}clearInvalidFields(e){e.forEach((e=>this.invalidFields.delete(e)))}}