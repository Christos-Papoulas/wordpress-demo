import { MbscDraggableOptions } from '../../core/components/draggable/draggable';
import { Draggable as DraggableComp } from '../../core/components/draggable/draggable.common';
declare class Draggable extends DraggableComp {
    static _selector: string;
    static _renderOpt: import("../../preact/renderer").IRenderOptions;
}
export declare const draggable: (selector: string | HTMLElement, options?: MbscDraggableOptions) => Draggable | {
    [key: string]: Draggable;
};
export { Draggable, MbscDraggableOptions, };
