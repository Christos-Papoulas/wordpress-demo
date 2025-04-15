import { ICalendarHeaderProp as MbscCalendarHeaderOptions } from '../../core/shared/calendar-view/calendar-header';
declare const CalendarNav: {
    ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
    _name: string;
};
declare const CalendarNext: {
    ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
    _name: string;
};
declare const CalendarPrev: {
    ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
    _name: string;
};
declare const CalendarToday: {
    ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
    _name: string;
};
export declare const calendarNext: (selector: string | HTMLElement, options?: MbscCalendarHeaderOptions) => {
    ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
    _name: string;
} | {
    [key: string]: {
        ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
        _name: string;
    };
};
export declare const calendarPrev: (selector: string | HTMLElement, options?: MbscCalendarHeaderOptions) => {
    ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
    _name: string;
} | {
    [key: string]: {
        ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
        _name: string;
    };
};
export declare const calendarToday: (selector: string | HTMLElement, options?: MbscCalendarHeaderOptions) => {
    ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
    _name: string;
} | {
    [key: string]: {
        ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
        _name: string;
    };
};
export declare const calendarNav: (selector: string | HTMLElement, options?: MbscCalendarHeaderOptions) => {
    ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
    _name: string;
} | {
    [key: string]: {
        ({ calendar, view, ...others }: MbscCalendarHeaderOptions): any;
        _name: string;
    };
};
export { CalendarNext, CalendarPrev, CalendarToday, CalendarNav, MbscCalendarHeaderOptions, };
