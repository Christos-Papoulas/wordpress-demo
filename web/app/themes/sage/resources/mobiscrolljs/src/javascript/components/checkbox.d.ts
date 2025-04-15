import { MbscCheckboxOptions } from '../../core/components/checkbox/checkbox';
import { Checkbox as CheckboxComp } from '../../core/components/checkbox/checkbox.common';
declare class Checkbox extends CheckboxComp {
    static _selector: string;
    static _renderOpt: import("../../preact/renderer").IRenderOptions;
}
export declare const checkbox: (selector: string | HTMLElement, options?: MbscCheckboxOptions) => Checkbox | {
    [key: string]: Checkbox;
};
export { Checkbox, MbscCheckboxOptions, };
