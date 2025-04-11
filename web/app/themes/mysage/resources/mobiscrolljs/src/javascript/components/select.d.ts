import { MbscSelectOptions } from '../../core/components/select/select';
import { Select } from '../../core/components/select/select.common';
export declare const select: (selector: string | HTMLElement, options?: MbscSelectOptions) => Select | {
    [key: string]: Select;
};
export { MbscPopupButton, MbscPopupDisplay, MbscPopupPredefinedButton } from '../../core/components/popup/popup';
export { getJson } from '../../core/util/http';
export { Select, MbscSelectOptions, };
