import t from "../core/Plugin";export default class e extends t{constructor(t){super(t);this.intlTelInstances=new Map;this.countryChangeHandler=new Map;this.fieldElements=new Map;this.opts=Object.assign({},{autoPlaceholder:"polite",utilsScript:""},t);this.validatePhoneNumber=this.checkPhoneNumber.bind(this);this.fields=typeof this.opts.field==="string"?this.opts.field.split(","):this.opts.field}install(){this.core.registerValidator(e.INT_TEL_VALIDATOR,this.validatePhoneNumber);this.fields.forEach((t=>{this.core.addField(t,{validators:{[e.INT_TEL_VALIDATOR]:{message:this.opts.message}}});const s=this.core.getElements(t)[0];const i=()=>this.core.revalidateField(t);s.addEventListener("countrychange",i);this.countryChangeHandler.set(t,i);this.fieldElements.set(t,s);this.intlTelInstances.set(t,intlTelInput(s,this.opts))}))}uninstall(){this.fields.forEach((t=>{const s=this.countryChangeHandler.get(t);const i=this.fieldElements.get(t);const n=this.getIntTelInstance(t);if(s&&i&&n){i.removeEventListener("countrychange",s);this.core.disableValidator(t,e.INT_TEL_VALIDATOR);n.destroy()}}))}getIntTelInstance(t){return this.intlTelInstances.get(t)}checkPhoneNumber(){return{validate: t=>{const e=t.value;const s=this.getIntTelInstance(t.field);if(e===""||!s){return{valid:true}}return{valid:s.isValidNumber()}}}}}e.INT_TEL_VALIDATOR="___InternationalTelephoneInputValidator";