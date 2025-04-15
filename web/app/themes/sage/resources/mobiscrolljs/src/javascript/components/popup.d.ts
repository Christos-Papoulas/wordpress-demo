import { MbscPopupButton, MbscPopupDisplay, MbscPopupOptions, MbscPopupPredefinedButton } from '../../core/components/popup/popup';
import { Popup } from '../../core/components/popup/popup.common';
export declare const popup: (selector: string | HTMLElement, options?: MbscPopupOptions) => Popup | {
    [key: string]: Popup;
};
export { MbscPopupButton, MbscPopupDisplay, MbscPopupOptions, MbscPopupPredefinedButton, Popup, };
