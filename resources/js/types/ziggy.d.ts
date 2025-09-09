declare module 'ziggy-js' {
    interface Config {
        url: string;
        port: number | null;
        defaults: Record<string, unknown>;
        routes: Record<string, unknown>;
    }

    interface Router {
        current(): string | undefined;
        current(name: string): boolean;
        has(name: string): boolean;
        get(name: string): Route | undefined;
        [key: string]: any;
    }

    interface Route {
        uri: string;
        methods: string[];
        parameters?: string[];
        bindings?: Record<string, string>;
        wheres?: Record<string, string>;
    }

    function route(name?: string, params?: any, absolute?: boolean, config?: Config): string | Router;
    
    export { route, Config, Router, Route };
    export default route;
}