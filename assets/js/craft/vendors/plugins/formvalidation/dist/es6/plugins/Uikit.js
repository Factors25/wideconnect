import e from "../utils/classSet";import t from "./Framework";export default class r extends t{constructor(e){super(Object.assign({},{formClass:"fv-plugins-uikit",messageClass:"uk-text-danger",rowInvalidClass:"uk-form-danger",rowPattern:/^.*(uk-form-controls|uk-width-[\d+]-[\d+]).*$/,rowSelector:".uk-margin",rowValidClass:"uk-form-success"},e))}onIconPlaced(t){const r=t.element.getAttribute("type");if("checkbox"===r||"radio"===r){const r=t.element.parentElement;e(t.iconElement,{"fv-plugins-icon-check":true});r.parentElement.insertBefore(t.iconElement,r.nextSibling)}}}