import e from "./Framework";export default class o extends e{constructor(e){super(Object.assign({},{formClass:"fv-plugins-foundation",messageClass:"form-error",rowInvalidClass:"fv-row__error",rowPattern:/^.*((small|medium|large)-[0-9]+)\s.*(cell).*$/,rowSelector:".grid-x",rowValidClass:"fv-row__success"},e))}onIconPlaced(e){const o=e.element.getAttribute("type");if("checkbox"===o||"radio"===o){const o=e.iconElement.nextSibling;if("LABEL"===o.nodeName){o.parentNode.insertBefore(e.iconElement,o.nextSibling)}else if("#text"===o.nodeName){const n=o.nextSibling;if(n&&"LABEL"===n.nodeName){n.parentNode.insertBefore(e.iconElement,n.nextSibling)}}}}}