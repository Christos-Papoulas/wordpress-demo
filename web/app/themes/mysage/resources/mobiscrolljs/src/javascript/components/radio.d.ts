import { MbscRadioOptions } from '../../core/components/radio/radio';
import { Radio as RadioComp } from '../../core/components/radio/radio.common';
declare class Radio extends RadioComp {
    static _selector: string;
    static _renderOpt: import("../../preact/renderer").IRenderOptions;
}
export declare const radio: (selector: string | HTMLElement, options?: MbscRadioOptions) => Radio | {
    [key: string]: Radio;
};
export { MbscRadioOptions, Radio, };
