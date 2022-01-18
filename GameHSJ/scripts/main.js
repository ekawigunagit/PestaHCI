'use strict';
window.DOMHandler = class {
    constructor(e, h) {
        this._iRuntime = e;
        this._componentId = h;
        this._hasTickCallback = !1;
        this._tickCallback = () => this.Tick()
    }
    Attach() {}
    PostToRuntime(e, h, g, l) {
        this._iRuntime.PostToRuntimeComponent(this._componentId, e, h, g, l)
    }
    PostToRuntimeAsync(e, h, g, l) {
        return this._iRuntime.PostToRuntimeComponentAsync(this._componentId, e, h, g, l)
    }
    _PostToRuntimeMaybeSync(e, h, g) {
        this._iRuntime.UsesWorker() ? this.PostToRuntime(e, h, g) : this._iRuntime._GetLocalRuntime()._OnMessageFromDOM({
            type: "event",
            component: this._componentId,
            handler: e,
            dispatchOpts: g || null,
            data: h,
            responseId: null
        })
    }
    AddRuntimeMessageHandler(e, h) {
        this._iRuntime.AddRuntimeComponentMessageHandler(this._componentId, e, h)
    }
    AddRuntimeMessageHandlers(e) {
        for (const [h, g] of e) this.AddRuntimeMessageHandler(h, g)
    }
    GetRuntimeInterface() {
        return this._iRuntime
    }
    GetComponentID() {
        return this._componentId
    }
    _StartTicking() {
        this._hasTickCallback || (this._iRuntime._AddRAFCallback(this._tickCallback), this._hasTickCallback = !0)
    }
    _StopTicking() {
        this._hasTickCallback &&
            (this._iRuntime._RemoveRAFCallback(this._tickCallback), this._hasTickCallback = !1)
    }
    Tick() {}
};
window.RateLimiter = class {
    constructor(e, h) {
        this._callback = e;
        this._interval = h;
        this._timerId = -1;
        this._lastCallTime = -Infinity;
        this._timerCallFunc = () => this._OnTimer();
        this._canRunImmediate = this._ignoreReset = !1
    }
    SetCanRunImmediate(e) {
        this._canRunImmediate = !!e
    }
    Call() {
        if (-1 === this._timerId) {
            var e = Date.now(),
                h = e - this._lastCallTime,
                g = this._interval;
            h >= g && this._canRunImmediate ? (this._lastCallTime = e, this._RunCallback()) : this._timerId = self.setTimeout(this._timerCallFunc, Math.max(g - h, 4))
        }
    }
    _RunCallback() {
        this._ignoreReset = !0;
        this._callback();
        this._ignoreReset = !1
    }
    Reset() {
        this._ignoreReset || (this._CancelTimer(), this._lastCallTime = Date.now())
    }
    _OnTimer() {
        this._timerId = -1;
        this._lastCallTime = Date.now();
        this._RunCallback()
    }
    _CancelTimer() {
        -1 !== this._timerId && (self.clearTimeout(this._timerId), this._timerId = -1)
    }
    Release() {
        this._CancelTimer();
        this._timerCallFunc = this._callback = null
    }
};
"use strict";
window.DOMElementHandler = class extends self.DOMHandler {
    constructor(e, h) {
        super(e, h);
        this._elementMap = new Map;
        this._autoAttach = !0;
        this.AddRuntimeMessageHandlers([
            ["create", g => this._OnCreate(g)],
            ["destroy", g => this._OnDestroy(g)],
            ["set-visible", g => this._OnSetVisible(g)],
            ["update-position", g => this._OnUpdatePosition(g)],
            ["update-state", g => this._OnUpdateState(g)],
            ["focus", g => this._OnSetFocus(g)],
            ["set-css-style", g => this._OnSetCssStyle(g)],
            ["set-attribute", g => this._OnSetAttribute(g)],
            ["remove-attribute",
                g => this._OnRemoveAttribute(g)
            ]
        ]);
        this.AddDOMElementMessageHandler("get-element", g => g)
    }
    SetAutoAttach(e) {
        this._autoAttach = !!e
    }
    AddDOMElementMessageHandler(e, h) {
        this.AddRuntimeMessageHandler(e, g => {
            const l = this._elementMap.get(g.elementId);
            return h(l, g)
        })
    }
    _OnCreate(e) {
        const h = e.elementId,
            g = this.CreateElement(h, e);
        this._elementMap.set(h, g);
        g.style.boxSizing = "border-box";
        e.isVisible || (g.style.display = "none");
        e = this._GetFocusElement(g);
        e.addEventListener("focus", l => this._OnFocus(h));
        e.addEventListener("blur",
            l => this._OnBlur(h));
        this._autoAttach && document.body.appendChild(g)
    }
    CreateElement(e, h) {
        throw Error("required override");
    }
    DestroyElement(e) {}
    _OnDestroy(e) {
        e = e.elementId;
        const h = this._elementMap.get(e);
        this.DestroyElement(h);
        this._autoAttach && h.parentElement.removeChild(h);
        this._elementMap.delete(e)
    }
    PostToRuntimeElement(e, h, g) {
        g || (g = {});
        g.elementId = h;
        this.PostToRuntime(e, g)
    }
    _PostToRuntimeElementMaybeSync(e, h, g) {
        g || (g = {});
        g.elementId = h;
        this._PostToRuntimeMaybeSync(e, g)
    }
    _OnSetVisible(e) {
        this._autoAttach &&
            (this._elementMap.get(e.elementId).style.display = e.isVisible ? "" : "none")
    }
    _OnUpdatePosition(e) {
        if (this._autoAttach) {
            var h = this._elementMap.get(e.elementId);
            h.style.left = e.left + "px";
            h.style.top = e.top + "px";
            h.style.width = e.width + "px";
            h.style.height = e.height + "px";
            e = e.fontSize;
            null !== e && (h.style.fontSize = e + "em")
        }
    }
    _OnUpdateState(e) {
        const h = this._elementMap.get(e.elementId);
        this.UpdateState(h, e)
    }
    UpdateState(e, h) {
        throw Error("required override");
    }
    _GetFocusElement(e) {
        return e
    }
    _OnFocus(e) {
        this.PostToRuntimeElement("elem-focused",
            e)
    }
    _OnBlur(e) {
        this.PostToRuntimeElement("elem-blurred", e)
    }
    _OnSetFocus(e) {
        const h = this._GetFocusElement(this._elementMap.get(e.elementId));
        e.focus ? h.focus() : h.blur()
    }
    _OnSetCssStyle(e) {
        this._elementMap.get(e.elementId).style[e.prop] = e.val
    }
    _OnSetAttribute(e) {
        this._elementMap.get(e.elementId).setAttribute(e.name, e.val)
    }
    _OnRemoveAttribute(e) {
        this._elementMap.get(e.elementId).removeAttribute(e.name)
    }
    GetElementById(e) {
        return this._elementMap.get(e)
    }
};
"use strict"; {
    const e = /(iphone|ipod|ipad|macos|macintosh|mac os x)/i.test(navigator.userAgent),
        h = /android/i.test(navigator.userAgent);
    let g = 0;

    function l(c) {
        const a = document.createElement("script");
        a.async = !1;
        a.type = "module";
        return c.isStringSrc ? new Promise(d => {
            const f = "c3_resolve_" + g;
            ++g;
            self[f] = d;
            a.textContent = c.str + `\n\nself["${f}"]();`;
            document.head.appendChild(a)
        }) : new Promise((d, f) => {
            a.onload = d;
            a.onerror = f;
            a.src = c;
            document.head.appendChild(a)
        })
    }
    let q = !1,
        v = !1;

    function w() {
        if (!q) {
            try {
                new Worker("blob://", {
                    get type() {
                        v = !0
                    }
                })
            } catch (c) {}
            q = !0
        }
        return v
    }
    let u = new Audio;
    const x = {
        "audio/webm; codecs=opus": !!u.canPlayType("audio/webm; codecs=opus"),
        "audio/ogg; codecs=opus": !!u.canPlayType("audio/ogg; codecs=opus"),
        "audio/webm; codecs=vorbis": !!u.canPlayType("audio/webm; codecs=vorbis"),
        "audio/ogg; codecs=vorbis": !!u.canPlayType("audio/ogg; codecs=vorbis"),
        "audio/mp4": !!u.canPlayType("audio/mp4"),
        "audio/mpeg": !!u.canPlayType("audio/mpeg")
    };
    u = null;
    async function H(c) {
        c = await E(c);
        return (new TextDecoder("utf-8")).decode(c)
    }

    function E(c) {
        return new Promise((a, d) => {
            const f = new FileReader;
            f.onload = k => a(k.target.result);
            f.onerror = k => d(k);
            f.readAsArrayBuffer(c)
        })
    }
    const y = [];
    let z = 0;
    window.RealFile = window.File;
    const A = [],
        D = new Map,
        t = new Map;
    let F = 0;
    const B = [];
    self.runOnStartup = function (c) {
        if ("function" !== typeof c) throw Error("runOnStartup called without a function");
        B.push(c)
    };
    const I = new Set(["cordova", "playable-ad", "instant-games"]);

    function G(c) {
        return I.has(c)
    }
    let b = !1;
    window.RuntimeInterface = class c {
        constructor(a) {
            this._useWorker =
                a.useWorker;
            this._messageChannelPort = null;
            this._baseUrl = "";
            this._scriptFolder = a.scriptFolder;
            this._workerScriptURLs = {};
            this._localRuntime = this._worker = null;
            this._domHandlers = [];
            this._jobScheduler = this._canvas = this._runtimeDomHandler = null;
            this._rafId = -1;
            this._rafFunc = () => this._OnRAFCallback();
            this._rafCallbacks = [];
            this._exportType = a.exportType;
            this._isFileProtocol = "file" === location.protocol.substr(0, 4);
            !this._useWorker || "undefined" !== typeof OffscreenCanvas && navigator.userActivation && w() || (this._useWorker = !1);
            if ("playable-ad" === this._exportType || "instant-games" === this._exportType) this._useWorker = !1;
            if ("cordova" === this._exportType && this._useWorker)
                if (h) {
                    const d = /Chrome\/(\d+)/i.exec(navigator.userAgent);
                    d && 90 <= parseInt(d[1], 10) || (this._useWorker = !1)
                } else this._useWorker = !1;
            this._localFileStrings = this._localFileBlobs = null;
            "html5" !== this._exportType && "playable-ad" !== this._exportType || !this._isFileProtocol || alert("Exported games won't work until you upload them. (When running on the file: protocol, browsers block many features from working for security reasons.)");
            "html5" !== this._exportType || window.isSecureContext || console.warn("[Construct 3] Warning: the browser indicates this is not a secure context. Some features may be unavailable. Use secure (HTTPS) hosting to ensure all features are available.");
            this.AddRuntimeComponentMessageHandler("runtime", "cordova-fetch-local-file", d => this._OnCordovaFetchLocalFile(d));
            this.AddRuntimeComponentMessageHandler("runtime", "create-job-worker", d => this._OnCreateJobWorker(d));
            "cordova" === this._exportType ? document.addEventListener("deviceready",
                () => this._Init(a)) : this._Init(a)
        }
        Release() {
            this._CancelAnimationFrame();
            this._messageChannelPort && (this._messageChannelPort = this._messageChannelPort.onmessage = null);
            this._worker && (this._worker.terminate(), this._worker = null);
            this._localRuntime && (this._localRuntime.Release(), this._localRuntime = null);
            this._canvas && (this._canvas.parentElement.removeChild(this._canvas), this._canvas = null)
        }
        GetCanvas() {
            return this._canvas
        }
        GetBaseURL() {
            return this._baseUrl
        }
        UsesWorker() {
            return this._useWorker
        }
        GetExportType() {
            return this._exportType
        }
        IsFileProtocol() {
            return this._isFileProtocol
        }
        GetScriptFolder() {
            return this._scriptFolder
        }
        IsiOSCordova() {
            return e &&
                "cordova" === this._exportType
        }
        IsiOSWebView() {
            const a = navigator.userAgent;
            return e && G(this._exportType) || navigator.standalone || /crios\/|fxios\/|edgios\//i.test(a)
        }
        IsAndroid() {
            return h
        }
        IsAndroidWebView() {
            return h && G(this._exportType)
        }
        async _Init(a) {
            "macos-wkwebview" === this._exportType && this._SendWrapperMessage({
                type: "ready"
            });
            if ("playable-ad" === this._exportType) {
                this._localFileBlobs = self.c3_base64files;
                this._localFileStrings = {};
                await this._ConvertDataUrisToBlobs();
                for (let f = 0, k = a.engineScripts.length; f <
                    k; ++f) {
                    var d = a.engineScripts[f].toLowerCase();
                    this._localFileStrings.hasOwnProperty(d) ? a.engineScripts[f] = {
                        isStringSrc: !0,
                        str: this._localFileStrings[d]
                    } : this._localFileBlobs.hasOwnProperty(d) && (a.engineScripts[f] = URL.createObjectURL(this._localFileBlobs[d]))
                }
            }
            a.baseUrl ? this._baseUrl = a.baseUrl : (d = location.origin, this._baseUrl = ("null" === d ? "file:///" : d) + location.pathname, d = this._baseUrl.lastIndexOf("/"), -1 !== d && (this._baseUrl = this._baseUrl.substr(0, d + 1)));
            a.workerScripts && (this._workerScriptURLs = a.workerScripts);
            d = new MessageChannel;
            this._messageChannelPort = d.port1;
            this._messageChannelPort.onmessage = f => this._OnMessageFromRuntime(f.data);
            window.c3_addPortMessageHandler && window.c3_addPortMessageHandler(f => this._OnMessageFromDebugger(f));
            this._jobScheduler = new self.JobSchedulerDOM(this);
            await this._jobScheduler.Init();
            "object" === typeof window.StatusBar && window.StatusBar.hide();
            "object" === typeof window.AndroidFullScreen && window.AndroidFullScreen.immersiveMode();
            this._useWorker ? await this._InitWorker(a, d.port2) :
                await this._InitDOM(a, d.port2)
        }
        _GetWorkerURL(a) {
            a = this._workerScriptURLs.hasOwnProperty(a) ? this._workerScriptURLs[a] : a.endsWith("/workermain.js") && this._workerScriptURLs.hasOwnProperty("workermain.js") ? this._workerScriptURLs["workermain.js"] : "playable-ad" === this._exportType && this._localFileBlobs.hasOwnProperty(a.toLowerCase()) ? this._localFileBlobs[a.toLowerCase()] : a;
            a instanceof Blob && (a = URL.createObjectURL(a));
            return a
        }
        async CreateWorker(a, d, f) {
            if (a.startsWith("blob:")) return new Worker(a, f);
            if ("cordova" ===
                this._exportType && this._isFileProtocol) return a = await this.CordovaFetchLocalFileAsArrayBuffer(f.isC3MainWorker ? a : this._scriptFolder + a), a = new Blob([a], {
                type: "application/javascript"
            }), new Worker(URL.createObjectURL(a), f);
            a = new URL(a, d);
            if (location.origin !== a.origin) {
                a = await fetch(a);
                if (!a.ok) throw Error("failed to fetch worker script");
                a = await a.blob();
                return new Worker(URL.createObjectURL(a), f)
            }
            return new Worker(a, f)
        }
        _GetWindowInnerWidth() {
            return Math.max(window.innerWidth, 1)
        }
        _GetWindowInnerHeight() {
            return Math.max(window.innerHeight,
                1)
        }
        _GetCommonRuntimeOptions(a) {
            return {
                baseUrl: this._baseUrl,
                windowInnerWidth: this._GetWindowInnerWidth(),
                windowInnerHeight: this._GetWindowInnerHeight(),
                devicePixelRatio: window.devicePixelRatio,
                isFullscreen: c.IsDocumentFullscreen(),
                projectData: a.projectData,
                previewImageBlobs: window.cr_previewImageBlobs || this._localFileBlobs,
                previewProjectFileBlobs: window.cr_previewProjectFileBlobs,
                previewProjectFileSWUrls: window.cr_previewProjectFiles,
                swClientId: window.cr_swClientId || "",
                exportType: a.exportType,
                isDebug: -1 <
                    self.location.search.indexOf("debug"),
                ife: !!self.ife,
                jobScheduler: this._jobScheduler.GetPortData(),
                supportedAudioFormats: x,
                opusWasmScriptUrl: window.cr_opusWasmScriptUrl || this._scriptFolder + "opus.wasm.js",
                opusWasmBinaryUrl: window.cr_opusWasmBinaryUrl || this._scriptFolder + "opus.wasm.wasm",
                isFileProtocol: this._isFileProtocol,
                isiOSCordova: this.IsiOSCordova(),
                isiOSWebView: this.IsiOSWebView(),
                isFBInstantAvailable: "undefined" !== typeof self.FBInstant
            }
        }
        async _InitWorker(a, d) {
            var f = this._GetWorkerURL(a.workerMainUrl);
            this._worker = await this.CreateWorker(f, this._baseUrl, {
                type: "module",
                name: "Runtime",
                isC3MainWorker: !0
            });
            this._canvas = document.createElement("canvas");
            this._canvas.style.display = "none";
            f = this._canvas.transferControlToOffscreen();
            document.body.appendChild(this._canvas);
            window.c3canvas = this._canvas;
            let k = a.workerDependencyScripts || [],
                n = a.engineScripts;
            k = await Promise.all(k.map(m => this._MaybeGetCordovaScriptURL(m)));
            n = await Promise.all(n.map(m => this._MaybeGetCordovaScriptURL(m)));
            if ("cordova" === this._exportType)
                for (let m =
                        0, p = a.projectScripts.length; m < p; ++m) {
                    const r = a.projectScripts[m],
                        C = r[0];
                    if (C === a.mainProjectScript || "scriptsInEvents.js" === C || C.endsWith("/scriptsInEvents.js")) r[1] = await this._MaybeGetCordovaScriptURL(C)
                }
            this._worker.postMessage(Object.assign(this._GetCommonRuntimeOptions(a), {
                type: "init-runtime",
                isInWorker: !0,
                messagePort: d,
                canvas: f,
                workerDependencyScripts: k,
                engineScripts: n,
                projectScripts: a.projectScripts,
                mainProjectScript: a.mainProjectScript,
                projectScriptsStatus: self.C3_ProjectScriptsStatus
            }), [d,
                f, ...this._jobScheduler.GetPortTransferables()
            ]);
            this._domHandlers = A.map(m => new m(this));
            this._FindRuntimeDOMHandler();
            self.c3_callFunction = (m, p) => this._runtimeDomHandler._InvokeFunctionFromJS(m, p);
            "preview" === this._exportType && (self.goToLastErrorScript = () => this.PostToRuntimeComponent("runtime", "go-to-last-error-script"))
        }
        async _InitDOM(a, d) {
            this._canvas = document.createElement("canvas");
            this._canvas.style.display = "none";
            document.body.appendChild(this._canvas);
            window.c3canvas = this._canvas;
            this._domHandlers =
                A.map(m => new m(this));
            this._FindRuntimeDOMHandler();
            var f = a.engineScripts.map(m => "string" === typeof m ? (new URL(m, this._baseUrl)).toString() : m);
            Array.isArray(a.workerDependencyScripts) && f.unshift(...a.workerDependencyScripts);
            f = await Promise.all(f.map(m => this._MaybeGetCordovaScriptURL(m)));
            await Promise.all(f.map(m => l(m)));
            f = self.C3_ProjectScriptsStatus;
            const k = a.mainProjectScript,
                n = a.projectScripts;
            for (let [m, p] of n)
                if (p || (p = m), m === k) try {
                    p = await this._MaybeGetCordovaScriptURL(p), await l(p), "preview" !==
                        this._exportType || f[m] || this._ReportProjectMainScriptError(m, "main script did not run to completion")
                } catch (r) {
                    this._ReportProjectMainScriptError(m, r)
                } else if ("scriptsInEvents.js" === m || m.endsWith("/scriptsInEvents.js")) p = await this._MaybeGetCordovaScriptURL(p), await l(p);
            "preview" === this._exportType && "object" !== typeof self.C3.ScriptsInEvents ? (this._RemoveLoadingMessage(), console.error("[C3 runtime] Failed to load JavaScript code used in events. Check all your JavaScript code has valid syntax."), alert("Failed to load JavaScript code used in events. Check all your JavaScript code has valid syntax.")) :
                (a = Object.assign(this._GetCommonRuntimeOptions(a), {
                    isInWorker: !1,
                    messagePort: d,
                    canvas: this._canvas,
                    runOnStartupFunctions: B
                }), this._OnBeforeCreateRuntime(), this._localRuntime = self.C3_CreateRuntime(a), await self.C3_InitRuntime(this._localRuntime, a))
        }
        _ReportProjectMainScriptError(a, d) {
            this._RemoveLoadingMessage();
            console.error(`[Preview] Failed to load project main script (${a}): `, d);
            alert(`Failed to load project main script (${a}). Check all your JavaScript code has valid syntax. Press F12 and check the console for error details.`)
        }
        _OnBeforeCreateRuntime() {
            this._RemoveLoadingMessage()
        }
        _RemoveLoadingMessage() {
            const a =
                window.cr_previewLoadingElem;
            a && (a.parentElement.removeChild(a), window.cr_previewLoadingElem = null)
        }
        async _OnCreateJobWorker(a) {
            a = await this._jobScheduler._CreateJobWorker();
            return {
                outputPort: a,
                transferables: [a]
            }
        }
        _GetLocalRuntime() {
            if (this._useWorker) throw Error("not available in worker mode");
            return this._localRuntime
        }
        PostToRuntimeComponent(a, d, f, k, n) {
            this._messageChannelPort.postMessage({
                type: "event",
                component: a,
                handler: d,
                dispatchOpts: k || null,
                data: f,
                responseId: null
            }, n)
        }
        PostToRuntimeComponentAsync(a,
            d, f, k, n) {
            const m = F++,
                p = new Promise((r, C) => {
                    t.set(m, {
                        resolve: r,
                        reject: C
                    })
                });
            this._messageChannelPort.postMessage({
                type: "event",
                component: a,
                handler: d,
                dispatchOpts: k || null,
                data: f,
                responseId: m
            }, n);
            return p
        } ["_OnMessageFromRuntime"](a) {
            const d = a.type;
            if ("event" === d) return this._OnEventFromRuntime(a);
            if ("result" === d) this._OnResultFromRuntime(a);
            else if ("runtime-ready" === d) this._OnRuntimeReady();
            else if ("alert-error" === d) this._RemoveLoadingMessage(), alert(a.message);
            else if ("creating-runtime" === d) this._OnBeforeCreateRuntime();
            else throw Error(`unknown message '${d}'`);
        }
        _OnEventFromRuntime(a) {
            const d = a.component,
                f = a.handler,
                k = a.data,
                n = a.responseId;
            if (a = D.get(d))
                if (a = a.get(f)) {
                    var m = null;
                    try {
                        m = a(k)
                    } catch (p) {
                        console.error(`Exception in '${d}' handler '${f}':`, p);
                        null !== n && this._PostResultToRuntime(n, !1, "" + p);
                        return
                    }
                    if (null === n) return m;
                    m && m.then ? m.then(p => this._PostResultToRuntime(n, !0, p)).catch(p => {
                        console.error(`Rejection from '${d}' handler '${f}':`, p);
                        this._PostResultToRuntime(n, !1, "" + p)
                    }) : this._PostResultToRuntime(n,
                        !0, m)
                } else console.warn(`[DOM] No handler '${f}' for component '${d}'`);
            else console.warn(`[DOM] No event handlers for component '${d}'`)
        }
        _PostResultToRuntime(a, d, f) {
            let k;
            f && f.transferables && (k = f.transferables);
            this._messageChannelPort.postMessage({
                type: "result",
                responseId: a,
                isOk: d,
                result: f
            }, k)
        }
        _OnResultFromRuntime(a) {
            const d = a.responseId,
                f = a.isOk;
            a = a.result;
            const k = t.get(d);
            f ? k.resolve(a) : k.reject(a);
            t.delete(d)
        }
        AddRuntimeComponentMessageHandler(a, d, f) {
            let k = D.get(a);
            k || (k = new Map, D.set(a, k));
            if (k.has(d)) throw Error(`[DOM] Component '${a}' already has handler '${d}'`);
            k.set(d, f)
        }
        static AddDOMHandlerClass(a) {
            if (A.includes(a)) throw Error("DOM handler already added");
            A.push(a)
        }
        _FindRuntimeDOMHandler() {
            for (const a of this._domHandlers)
                if ("runtime" === a.GetComponentID()) {
                    this._runtimeDomHandler = a;
                    return
                } throw Error("cannot find runtime DOM handler");
        }
        _OnMessageFromDebugger(a) {
            this.PostToRuntimeComponent("debugger", "message", a)
        }
        _OnRuntimeReady() {
            for (const a of this._domHandlers) a.Attach()
        }
        static IsDocumentFullscreen() {
            return !!(document.fullscreenElement || document.webkitFullscreenElement ||
                document.mozFullScreenElement || b)
        }
        static _SetWrapperIsFullscreenFlag(a) {
            b = !!a
        }
        async GetRemotePreviewStatusInfo() {
            return await this.PostToRuntimeComponentAsync("runtime", "get-remote-preview-status-info")
        }
        _AddRAFCallback(a) {
            this._rafCallbacks.push(a);
            this._RequestAnimationFrame()
        }
        _RemoveRAFCallback(a) {
            a = this._rafCallbacks.indexOf(a);
            if (-1 === a) throw Error("invalid callback");
            this._rafCallbacks.splice(a, 1);
            this._rafCallbacks.length || this._CancelAnimationFrame()
        }
        _RequestAnimationFrame() {
            -1 === this._rafId &&
                this._rafCallbacks.length && (this._rafId = requestAnimationFrame(this._rafFunc))
        }
        _CancelAnimationFrame() {
            -1 !== this._rafId && (cancelAnimationFrame(this._rafId), this._rafId = -1)
        }
        _OnRAFCallback() {
            this._rafId = -1;
            for (const a of this._rafCallbacks) a();
            this._RequestAnimationFrame()
        }
        TryPlayMedia(a) {
            this._runtimeDomHandler.TryPlayMedia(a)
        }
        RemovePendingPlay(a) {
            this._runtimeDomHandler.RemovePendingPlay(a)
        }
        _PlayPendingMedia() {
            this._runtimeDomHandler._PlayPendingMedia()
        }
        SetSilent(a) {
            this._runtimeDomHandler.SetSilent(a)
        }
        IsAudioFormatSupported(a) {
            return !!x[a]
        }
        async _WasmDecodeWebMOpus(a) {
            a =
                await this.PostToRuntimeComponentAsync("runtime", "opus-decode", {
                    arrayBuffer: a
                }, null, [a]);
            return new Float32Array(a)
        }
        IsAbsoluteURL(a) {
            return /^(?:[a-z\-]+:)?\/\//.test(a) || "data:" === a.substr(0, 5) || "blob:" === a.substr(0, 5)
        }
        IsRelativeURL(a) {
            return !this.IsAbsoluteURL(a)
        }
        async _MaybeGetCordovaScriptURL(a) {
            return "cordova" === this._exportType && (a.startsWith("file:") || this._isFileProtocol && this.IsRelativeURL(a)) ? (a.startsWith(this._baseUrl) && (a = a.substr(this._baseUrl.length)), a = await this.CordovaFetchLocalFileAsArrayBuffer(a),
                a = new Blob([a], {
                    type: "application/javascript"
                }), URL.createObjectURL(a)) : a
        }
        async _OnCordovaFetchLocalFile(a) {
            const d = a.filename;
            switch (a.as) {
                case "text":
                    return await this.CordovaFetchLocalFileAsText(d);
                case "buffer":
                    return await this.CordovaFetchLocalFileAsArrayBuffer(d);
                default:
                    throw Error("unsupported type");
            }
        }
        _GetPermissionAPI() {
            const a = window.cordova && window.cordova.plugins && window.cordova.plugins.permissions;
            if ("object" !== typeof a) throw Error("Permission API is not loaded");
            return a
        }
        _MapPermissionID(a,
            d) {
            a = a[d];
            if ("string" !== typeof a) throw Error("Invalid permission name");
            return a
        }
        _HasPermission(a) {
            const d = this._GetPermissionAPI();
            return new Promise((f, k) => d.checkPermission(this._MapPermissionID(d, a), n => f(!!n.hasPermission), k))
        }
        _RequestPermission(a) {
            const d = this._GetPermissionAPI();
            return new Promise((f, k) => d.requestPermission(this._MapPermissionID(d, a), n => f(!!n.hasPermission), k))
        }
        async RequestPermissions(a) {
            if ("cordova" !== this.GetExportType() || this.IsiOSCordova()) return !0;
            for (const d of a)
                if (!await this._HasPermission(d) &&
                    !1 === await this._RequestPermission(d)) return !1;
            return !0
        }
        async RequirePermissions(...a) {
            if (!1 === await this.RequestPermissions(a)) throw Error("Permission not granted");
        }
        CordovaFetchLocalFile(a) {
            const d = window.cordova.file.applicationDirectory + "www/" + a.toLowerCase();
            return new Promise((f, k) => {
                window.resolveLocalFileSystemURL(d, n => {
                    n.file(f, k)
                }, k)
            })
        }
        async CordovaFetchLocalFileAsText(a) {
            a = await this.CordovaFetchLocalFile(a);
            return await H(a)
        }
        _CordovaMaybeStartNextArrayBufferRead() {
            if (y.length && !(8 <= z)) {
                z++;
                var a = y.shift();
                this._CordovaDoFetchLocalFileAsAsArrayBuffer(a.filename, a.successCallback, a.errorCallback)
            }
        }
        CordovaFetchLocalFileAsArrayBuffer(a) {
            return new Promise((d, f) => {
                y.push({
                    filename: a,
                    successCallback: k => {
                        z--;
                        this._CordovaMaybeStartNextArrayBufferRead();
                        d(k)
                    },
                    errorCallback: k => {
                        z--;
                        this._CordovaMaybeStartNextArrayBufferRead();
                        f(k)
                    }
                });
                this._CordovaMaybeStartNextArrayBufferRead()
            })
        }
        async _CordovaDoFetchLocalFileAsAsArrayBuffer(a, d, f) {
            try {
                const k = await this.CordovaFetchLocalFile(a),
                    n = await E(k);
                d(n)
            } catch (k) {
                f(k)
            }
        }
        _SendWrapperMessage(a) {
            if ("windows-webview2" === this._exportType) window.chrome.webview.postMessage(JSON.stringify(a));
            else if ("macos-wkwebview" === this._exportType) window.webkit.messageHandlers.C3Wrapper.postMessage(JSON.stringify(a));
            else throw Error("cannot send wrapper message");
        }
        async _ConvertDataUrisToBlobs() {
            const a = [];
            for (const [d, f] of Object.entries(this._localFileBlobs)) a.push(this._ConvertDataUriToBlobs(d, f));
            await Promise.all(a)
        }
        async _ConvertDataUriToBlobs(a, d) {
            if ("object" ===
                typeof d) this._localFileBlobs[a] = new Blob([d.str], {
                type: d.type
            }), this._localFileStrings[a] = d.str;
            else {
                let f = await this._FetchDataUri(d);
                f || (f = this._DataURIToBinaryBlobSync(d));
                this._localFileBlobs[a] = f
            }
        }
        async _FetchDataUri(a) {
            try {
                return await (await fetch(a)).blob()
            } catch (d) {
                return console.warn("Failed to fetch a data: URI. Falling back to a slower workaround. This is probably because the Content Security Policy unnecessarily blocked it. Allow data: URIs in your CSP to avoid this.", d), null
            }
        }
        _DataURIToBinaryBlobSync(a) {
            a =
                this._ParseDataURI(a);
            return this._BinaryStringToBlob(a.data, a.mime_type)
        }
        _ParseDataURI(a) {
            var d = a.indexOf(",");
            if (0 > d) throw new URIError("expected comma in data: uri");
            var f = a.substring(5, d);
            a = a.substring(d + 1);
            d = f.split(";");
            f = d[0] || "";
            const k = d[2];
            a = "base64" === d[1] || "base64" === k ? atob(a) : decodeURIComponent(a);
            return {
                mime_type: f,
                data: a
            }
        }
        _BinaryStringToBlob(a, d) {
            var f = a.length;
            let k = f >> 2,
                n = new Uint8Array(f),
                m = new Uint32Array(n.buffer, 0, k),
                p, r;
            for (r = p = 0; p < k; ++p) m[p] = a.charCodeAt(r++) | a.charCodeAt(r++) <<
                8 | a.charCodeAt(r++) << 16 | a.charCodeAt(r++) << 24;
            for (f &= 3; f--;) n[r] = a.charCodeAt(r), ++r;
            return new Blob([n], {
                type: d
            })
        }
    }
}
"use strict"; {
    const e = self.RuntimeInterface;

    function h(b) {
        return b.sourceCapabilities && b.sourceCapabilities.firesTouchEvents || b.originalEvent && b.originalEvent.sourceCapabilities && b.originalEvent.sourceCapabilities.firesTouchEvents
    }
    const g = new Map([
            ["OSLeft", "MetaLeft"],
            ["OSRight", "MetaRight"]
        ]),
        l = {
            dispatchRuntimeEvent: !0,
            dispatchUserScriptEvent: !0
        },
        q = {
            dispatchUserScriptEvent: !0
        },
        v = {
            dispatchRuntimeEvent: !0
        };

    function w(b) {
        return new Promise((c, a) => {
            const d = document.createElement("link");
            d.onload = () => c(d);
            d.onerror =
                f => a(f);
            d.rel = "stylesheet";
            d.href = b;
            document.head.appendChild(d)
        })
    }

    function u(b) {
        return new Promise((c, a) => {
            const d = new Image;
            d.onload = () => c(d);
            d.onerror = f => a(f);
            d.src = b
        })
    }
    async function x(b) {
        b = URL.createObjectURL(b);
        try {
            return await u(b)
        } finally {
            URL.revokeObjectURL(b)
        }
    }

    function H(b) {
        return new Promise((c, a) => {
            let d = new FileReader;
            d.onload = f => c(f.target.result);
            d.onerror = f => a(f);
            d.readAsText(b)
        })
    }
    async function E(b, c, a) {
        if (!/firefox/i.test(navigator.userAgent)) return await x(b);
        var d = await H(b);
        d =
            (new DOMParser).parseFromString(d, "image/svg+xml");
        const f = d.documentElement;
        if (f.hasAttribute("width") && f.hasAttribute("height")) {
            const k = f.getAttribute("width"),
                n = f.getAttribute("height");
            if (!k.includes("%") && !n.includes("%")) return await x(b)
        }
        f.setAttribute("width", c + "px");
        f.setAttribute("height", a + "px");
        d = (new XMLSerializer).serializeToString(d);
        b = new Blob([d], {
            type: "image/svg+xml"
        });
        return await x(b)
    }

    function y(b) {
        do {
            if (b.parentNode && b.hasAttribute("contenteditable")) return !0;
            b = b.parentNode
        } while (b);
        return !1
    }
    const z = new Set(["input", "textarea", "datalist", "select"]);

    function A(b) {
        return z.has(b.tagName.toLowerCase()) || y(b)
    }
    const D = new Set(["canvas", "body", "html"]);

    function t(b) {
        const c = b.target.tagName.toLowerCase();
        D.has(c) && b.preventDefault()
    }

    function F(b) {
        (b.metaKey || b.ctrlKey) && b.preventDefault()
    }
    self.C3_GetSvgImageSize = async function (b) {
        b = await x(b);
        if (0 < b.width && 0 < b.height) return [b.width, b.height]; {
            b.style.position = "absolute";
            b.style.left = "0px";
            b.style.top = "0px";
            b.style.visibility = "hidden";
            document.body.appendChild(b);
            const c = b.getBoundingClientRect();
            document.body.removeChild(b);
            return [c.width, c.height]
        }
    };
    self.C3_RasterSvgImageBlob = async function (b, c, a, d, f) {
        b = await E(b, c, a);
        const k = document.createElement("canvas");
        k.width = d;
        k.height = f;
        k.getContext("2d").drawImage(b, 0, 0, c, a);
        return k
    };
    let B = !1;
    document.addEventListener("pause", () => B = !0);
    document.addEventListener("resume", () => B = !1);

    function I() {
        try {
            return window.parent && window.parent.document.hasFocus()
        } catch (b) {
            return !1
        }
    }

    function G() {
        const b =
            document.activeElement;
        if (!b) return !1;
        const c = b.tagName.toLowerCase(),
            a = new Set("email number password search tel text url".split(" "));
        return "textarea" === c ? !0 : "input" === c ? a.has(b.type.toLowerCase() || "text") : y(b)
    }
    e.AddDOMHandlerClass(class extends self.DOMHandler {
        constructor(b) {
            super(b, "runtime");
            this._isFirstSizeUpdate = !0;
            this._simulatedResizeTimerId = -1;
            this._targetOrientation = "any";
            this._attachedDeviceMotionEvent = this._attachedDeviceOrientationEvent = !1;
            this._lastPointerRawUpdateEvent = this._pointerRawUpdateRateLimiter =
                this._debugHighlightElem = null;
            this._pointerRawMovementY = this._pointerRawMovementX = 0;
            this._lastWindowWidth = b._GetWindowInnerWidth();
            this._lastWindowHeight = b._GetWindowInnerHeight();
            this._virtualKeyboardHeight = 0;
            b.AddRuntimeComponentMessageHandler("canvas", "update-size", d => this._OnUpdateCanvasSize(d));
            b.AddRuntimeComponentMessageHandler("runtime", "invoke-download", d => this._OnInvokeDownload(d));
            b.AddRuntimeComponentMessageHandler("runtime", "raster-svg-image", d => this._OnRasterSvgImage(d));
            b.AddRuntimeComponentMessageHandler("runtime",
                "get-svg-image-size", d => this._OnGetSvgImageSize(d));
            b.AddRuntimeComponentMessageHandler("runtime", "set-target-orientation", d => this._OnSetTargetOrientation(d));
            b.AddRuntimeComponentMessageHandler("runtime", "register-sw", () => this._OnRegisterSW());
            b.AddRuntimeComponentMessageHandler("runtime", "post-to-debugger", d => this._OnPostToDebugger(d));
            b.AddRuntimeComponentMessageHandler("runtime", "go-to-script", d => this._OnPostToDebugger(d));
            b.AddRuntimeComponentMessageHandler("runtime", "before-start-ticking", () =>
                this._OnBeforeStartTicking());
            b.AddRuntimeComponentMessageHandler("runtime", "debug-highlight", d => this._OnDebugHighlight(d));
            b.AddRuntimeComponentMessageHandler("runtime", "enable-device-orientation", () => this._AttachDeviceOrientationEvent());
            b.AddRuntimeComponentMessageHandler("runtime", "enable-device-motion", () => this._AttachDeviceMotionEvent());
            b.AddRuntimeComponentMessageHandler("runtime", "add-stylesheet", d => this._OnAddStylesheet(d));
            b.AddRuntimeComponentMessageHandler("runtime", "alert", d => this._OnAlert(d));
            b.AddRuntimeComponentMessageHandler("runtime", "hide-cordova-splash", () => this._OnHideCordovaSplash());
            const c = new Set(["input", "textarea", "datalist"]);
            window.addEventListener("contextmenu", d => {
                const f = d.target,
                    k = f.tagName.toLowerCase();
                c.has(k) || y(f) || d.preventDefault()
            });
            const a = b.GetCanvas();
            window.addEventListener("selectstart", t);
            window.addEventListener("gesturehold", t);
            a.addEventListener("selectstart", t);
            a.addEventListener("gesturehold", t);
            window.addEventListener("touchstart", t, {
                passive: !1
            });
            "undefined" !== typeof PointerEvent ? (window.addEventListener("pointerdown", t, {
                passive: !1
            }), a.addEventListener("pointerdown", t)) : a.addEventListener("touchstart", t);
            this._mousePointerLastButtons = 0;
            window.addEventListener("mousedown", d => {
                1 === d.button && d.preventDefault()
            });
            window.addEventListener("mousewheel", F, {
                passive: !1
            });
            window.addEventListener("wheel", F, {
                passive: !1
            });
            window.addEventListener("resize", () => this._OnWindowResize());
            window.addEventListener("fullscreenchange", () => this._OnFullscreenChange());
            window.addEventListener("webkitfullscreenchange", () => this._OnFullscreenChange());
            window.addEventListener("mozfullscreenchange", () => this._OnFullscreenChange());
            window.addEventListener("fullscreenerror", d => this._OnFullscreenError(d));
            window.addEventListener("webkitfullscreenerror", d => this._OnFullscreenError(d));
            window.addEventListener("mozfullscreenerror", d => this._OnFullscreenError(d));
            b.IsiOSWebView() && window.addEventListener("focusout", () => {
                G() || (document.scrollingElement.scrollTop = 0)
            });
            self.C3WrapperOnMessage =
                d => this._OnWrapperMessage(d);
            this._mediaPendingPlay = new Set;
            this._mediaRemovedPendingPlay = new WeakSet;
            this._isSilent = !1
        }
        _OnBeforeStartTicking() {
            "cordova" === this._iRuntime.GetExportType() ? (document.addEventListener("pause", () => this._OnVisibilityChange(!0)), document.addEventListener("resume", () => this._OnVisibilityChange(!1))) : document.addEventListener("visibilitychange", () => this._OnVisibilityChange(document.hidden));
            return {
                isSuspended: !(!document.hidden && !B)
            }
        }
        Attach() {
            window.addEventListener("focus",
                () => this._PostRuntimeEvent("window-focus"));
            window.addEventListener("blur", () => {
                this._PostRuntimeEvent("window-blur", {
                    parentHasFocus: I()
                });
                this._mousePointerLastButtons = 0
            });
            window.addEventListener("focusin", c => {
                A(c.target) && this._PostRuntimeEvent("keyboard-blur")
            });
            window.addEventListener("keydown", c => this._OnKeyEvent("keydown", c));
            window.addEventListener("keyup", c => this._OnKeyEvent("keyup", c));
            window.addEventListener("dblclick", c => this._OnMouseEvent("dblclick", c, l));
            window.addEventListener("wheel",
                c => this._OnMouseWheelEvent("wheel", c));
            "undefined" !== typeof PointerEvent ? (window.addEventListener("pointerdown", c => {
                    this._HandlePointerDownFocus(c);
                    this._OnPointerEvent("pointerdown", c)
                }), this._iRuntime.UsesWorker() && "undefined" !== typeof window.onpointerrawupdate && self === self.top ? (this._pointerRawUpdateRateLimiter = new self.RateLimiter(() => this._DoSendPointerRawUpdate(), 5), this._pointerRawUpdateRateLimiter.SetCanRunImmediate(!0), window.addEventListener("pointerrawupdate", c => this._OnPointerRawUpdate(c))) :
                window.addEventListener("pointermove", c => this._OnPointerEvent("pointermove", c)), window.addEventListener("pointerup", c => this._OnPointerEvent("pointerup", c)), window.addEventListener("pointercancel", c => this._OnPointerEvent("pointercancel", c))) : (window.addEventListener("mousedown", c => {
                this._HandlePointerDownFocus(c);
                this._OnMouseEventAsPointer("pointerdown", c)
            }), window.addEventListener("mousemove", c => this._OnMouseEventAsPointer("pointermove", c)), window.addEventListener("mouseup", c => this._OnMouseEventAsPointer("pointerup",
                c)), window.addEventListener("touchstart", c => {
                this._HandlePointerDownFocus(c);
                this._OnTouchEvent("pointerdown", c)
            }), window.addEventListener("touchmove", c => this._OnTouchEvent("pointermove", c)), window.addEventListener("touchend", c => this._OnTouchEvent("pointerup", c)), window.addEventListener("touchcancel", c => this._OnTouchEvent("pointercancel", c)));
            const b = () => this._PlayPendingMedia();
            window.addEventListener("pointerup", b, !0);
            window.addEventListener("touchend", b, !0);
            window.addEventListener("click", b, !0);
            window.addEventListener("keydown", b, !0);
            window.addEventListener("gamepadconnected", b, !0);
            this._iRuntime.IsAndroid() && !this._iRuntime.IsAndroidWebView() && navigator.virtualKeyboard && (navigator.virtualKeyboard.overlaysContent = !0, navigator.virtualKeyboard.addEventListener("geometrychange", () => {
                this._OnAndroidVirtualKeyboardChange(this._GetWindowInnerHeight(), navigator.virtualKeyboard.boundingRect.height)
            }))
        }
        _OnAndroidVirtualKeyboardChange(b, c) {
            document.body.style.transform = "";
            if (0 < c) {
                var a = document.activeElement;
                a && (a = a.getBoundingClientRect(), b = (a.top + a.bottom) / 2 - (b - c) / 2, b > c && (b = c), 0 > b && (b = 0), 0 < b && (document.body.style.transform = `translateY(${-b}px)`))
            }
        }
        _PostRuntimeEvent(b, c) {
            this.PostToRuntime(b, c || null, v)
        }
        _GetWindowInnerWidth() {
            return this._iRuntime._GetWindowInnerWidth()
        }
        _GetWindowInnerHeight() {
            return this._iRuntime._GetWindowInnerHeight()
        }
        _OnWindowResize() {
            const b = this._GetWindowInnerWidth(),
                c = this._GetWindowInnerHeight();
            if (this._iRuntime.IsAndroidWebView()) {
                if (this._lastWindowWidth === b && c < this._lastWindowHeight) {
                    this._virtualKeyboardHeight =
                        this._lastWindowHeight - c;
                    this._OnAndroidVirtualKeyboardChange(this._lastWindowHeight, this._virtualKeyboardHeight);
                    return
                }
                0 < this._virtualKeyboardHeight && (this._virtualKeyboardHeight = 0, this._OnAndroidVirtualKeyboardChange(c, this._virtualKeyboardHeight));
                this._lastWindowWidth = b;
                this._lastWindowHeight = c
            }
            this._PostRuntimeEvent("window-resize", {
                innerWidth: b,
                innerHeight: c,
                devicePixelRatio: window.devicePixelRatio,
                isFullscreen: e.IsDocumentFullscreen()
            });
            this._iRuntime.IsiOSWebView() && (-1 !== this._simulatedResizeTimerId &&
                clearTimeout(this._simulatedResizeTimerId), this._OnSimulatedResize(b, c, 0))
        }
        _ScheduleSimulatedResize(b, c, a) {
            -1 !== this._simulatedResizeTimerId && clearTimeout(this._simulatedResizeTimerId);
            this._simulatedResizeTimerId = setTimeout(() => this._OnSimulatedResize(b, c, a), 48)
        }
        _OnSimulatedResize(b, c, a) {
            const d = this._GetWindowInnerWidth(),
                f = this._GetWindowInnerHeight();
            this._simulatedResizeTimerId = -1;
            d != b || f != c ? this._PostRuntimeEvent("window-resize", {
                innerWidth: d,
                innerHeight: f,
                devicePixelRatio: window.devicePixelRatio,
                isFullscreen: e.IsDocumentFullscreen()
            }) : 10 > a && this._ScheduleSimulatedResize(d, f, a + 1)
        }
        _OnSetTargetOrientation(b) {
            this._targetOrientation = b.targetOrientation
        }
        _TrySetTargetOrientation() {
            const b = this._targetOrientation;
            if (screen.orientation && screen.orientation.lock) screen.orientation.lock(b).catch(c => console.warn("[Construct 3] Failed to lock orientation: ", c));
            else try {
                let c = !1;
                screen.lockOrientation ? c = screen.lockOrientation(b) : screen.webkitLockOrientation ? c = screen.webkitLockOrientation(b) : screen.mozLockOrientation ?
                    c = screen.mozLockOrientation(b) : screen.msLockOrientation && (c = screen.msLockOrientation(b));
                c || console.warn("[Construct 3] Failed to lock orientation")
            } catch (c) {
                console.warn("[Construct 3] Failed to lock orientation: ", c)
            }
        }
        _OnFullscreenChange() {
            const b = e.IsDocumentFullscreen();
            b && "any" !== this._targetOrientation && this._TrySetTargetOrientation();
            this.PostToRuntime("fullscreenchange", {
                isFullscreen: b,
                innerWidth: this._GetWindowInnerWidth(),
                innerHeight: this._GetWindowInnerHeight()
            })
        }
        _OnFullscreenError(b) {
            console.warn("[Construct 3] Fullscreen request failed: ",
                b);
            this.PostToRuntime("fullscreenerror", {
                isFullscreen: e.IsDocumentFullscreen(),
                innerWidth: this._GetWindowInnerWidth(),
                innerHeight: this._GetWindowInnerHeight()
            })
        }
        _OnVisibilityChange(b) {
            b ? this._iRuntime._CancelAnimationFrame() : this._iRuntime._RequestAnimationFrame();
            this.PostToRuntime("visibilitychange", {
                hidden: b
            })
        }
        _OnKeyEvent(b, c) {
            "Backspace" === c.key && t(c);
            const a = g.get(c.code) || c.code;
            this._PostToRuntimeMaybeSync(b, {
                code: a,
                key: c.key,
                which: c.which,
                repeat: c.repeat,
                altKey: c.altKey,
                ctrlKey: c.ctrlKey,
                metaKey: c.metaKey,
                shiftKey: c.shiftKey,
                timeStamp: c.timeStamp
            }, l)
        }
        _OnMouseWheelEvent(b, c) {
            this.PostToRuntime(b, {
                clientX: c.clientX,
                clientY: c.clientY,
                pageX: c.pageX,
                pageY: c.pageY,
                deltaX: c.deltaX,
                deltaY: c.deltaY,
                deltaZ: c.deltaZ,
                deltaMode: c.deltaMode,
                timeStamp: c.timeStamp
            }, l)
        }
        _OnMouseEvent(b, c, a) {
            h(c) || this._PostToRuntimeMaybeSync(b, {
                button: c.button,
                buttons: c.buttons,
                clientX: c.clientX,
                clientY: c.clientY,
                pageX: c.pageX,
                pageY: c.pageY,
                movementX: c.movementX || 0,
                movementY: c.movementY || 0,
                timeStamp: c.timeStamp
            }, a)
        }
        _OnMouseEventAsPointer(b,
            c) {
            if (!h(c)) {
                var a = this._mousePointerLastButtons;
                "pointerdown" === b && 0 !== a ? b = "pointermove" : "pointerup" === b && 0 !== c.buttons && (b = "pointermove");
                this._PostToRuntimeMaybeSync(b, {
                    pointerId: 1,
                    pointerType: "mouse",
                    button: c.button,
                    buttons: c.buttons,
                    lastButtons: a,
                    clientX: c.clientX,
                    clientY: c.clientY,
                    pageX: c.pageX,
                    pageY: c.pageY,
                    movementX: c.movementX || 0,
                    movementY: c.movementY || 0,
                    width: 0,
                    height: 0,
                    pressure: 0,
                    tangentialPressure: 0,
                    tiltX: 0,
                    tiltY: 0,
                    twist: 0,
                    timeStamp: c.timeStamp
                }, l);
                this._mousePointerLastButtons = c.buttons;
                this._OnMouseEvent(c.type, c, q)
            }
        }
        _OnPointerEvent(b, c) {
            this._pointerRawUpdateRateLimiter && "pointermove" !== b && this._pointerRawUpdateRateLimiter.Reset();
            var a = 0;
            "mouse" === c.pointerType && (a = this._mousePointerLastButtons);
            this._PostToRuntimeMaybeSync(b, {
                pointerId: c.pointerId,
                pointerType: c.pointerType,
                button: c.button,
                buttons: c.buttons,
                lastButtons: a,
                clientX: c.clientX,
                clientY: c.clientY,
                pageX: c.pageX,
                pageY: c.pageY,
                movementX: (c.movementX || 0) + this._pointerRawMovementX,
                movementY: (c.movementY || 0) + this._pointerRawMovementY,
                width: c.width || 0,
                height: c.height || 0,
                pressure: c.pressure || 0,
                tangentialPressure: c.tangentialPressure || 0,
                tiltX: c.tiltX || 0,
                tiltY: c.tiltY || 0,
                twist: c.twist || 0,
                timeStamp: c.timeStamp
            }, l);
            this._pointerRawMovementY = this._pointerRawMovementX = 0;
            "mouse" === c.pointerType && (a = "mousemove", "pointerdown" === b ? a = "mousedown" : "pointerup" === b && (a = "mouseup"), this._OnMouseEvent(a, c, q), this._mousePointerLastButtons = c.buttons)
        }
        _OnPointerRawUpdate(b) {
            this._lastPointerRawUpdateEvent && (this._pointerRawMovementX += this._lastPointerRawUpdateEvent.movementX ||
                0, this._pointerRawMovementY += this._lastPointerRawUpdateEvent.movementY || 0);
            this._lastPointerRawUpdateEvent = b;
            this._pointerRawUpdateRateLimiter.Call()
        }
        _DoSendPointerRawUpdate() {
            this._OnPointerEvent("pointermove", this._lastPointerRawUpdateEvent);
            this._lastPointerRawUpdateEvent = null
        }
        _OnTouchEvent(b, c) {
            for (let a = 0, d = c.changedTouches.length; a < d; ++a) {
                const f = c.changedTouches[a];
                this._PostToRuntimeMaybeSync(b, {
                    pointerId: f.identifier,
                    pointerType: "touch",
                    button: 0,
                    buttons: 0,
                    lastButtons: 0,
                    clientX: f.clientX,
                    clientY: f.clientY,
                    pageX: f.pageX,
                    pageY: f.pageY,
                    movementX: c.movementX || 0,
                    movementY: c.movementY || 0,
                    width: 2 * (f.radiusX || f.webkitRadiusX || 0),
                    height: 2 * (f.radiusY || f.webkitRadiusY || 0),
                    pressure: f.force || f.webkitForce || 0,
                    tangentialPressure: 0,
                    tiltX: 0,
                    tiltY: 0,
                    twist: f.rotationAngle || 0,
                    timeStamp: c.timeStamp
                }, l)
            }
        }
        _HandlePointerDownFocus(b) {
            window !== window.top && window.focus();
            this._IsElementCanvasOrDocument(b.target) && document.activeElement && !this._IsElementCanvasOrDocument(document.activeElement) && document.activeElement.blur()
        }
        _IsElementCanvasOrDocument(b) {
            return !b ||
                b === document || b === window || b === document.body || "canvas" === b.tagName.toLowerCase()
        }
        _AttachDeviceOrientationEvent() {
            this._attachedDeviceOrientationEvent || (this._attachedDeviceOrientationEvent = !0, window.addEventListener("deviceorientation", b => this._OnDeviceOrientation(b)), window.addEventListener("deviceorientationabsolute", b => this._OnDeviceOrientationAbsolute(b)))
        }
        _AttachDeviceMotionEvent() {
            this._attachedDeviceMotionEvent || (this._attachedDeviceMotionEvent = !0, window.addEventListener("devicemotion", b => this._OnDeviceMotion(b)))
        }
        _OnDeviceOrientation(b) {
            this.PostToRuntime("deviceorientation", {
                absolute: !!b.absolute,
                alpha: b.alpha || 0,
                beta: b.beta || 0,
                gamma: b.gamma || 0,
                timeStamp: b.timeStamp,
                webkitCompassHeading: b.webkitCompassHeading,
                webkitCompassAccuracy: b.webkitCompassAccuracy
            }, l)
        }
        _OnDeviceOrientationAbsolute(b) {
            this.PostToRuntime("deviceorientationabsolute", {
                absolute: !!b.absolute,
                alpha: b.alpha || 0,
                beta: b.beta || 0,
                gamma: b.gamma || 0,
                timeStamp: b.timeStamp
            }, l)
        }
        _OnDeviceMotion(b) {
            let c = null;
            var a = b.acceleration;
            a && (c = {
                x: a.x || 0,
                y: a.y || 0,
                z: a.z || 0
            });
            a = null;
            var d = b.accelerationIncludingGravity;
            d && (a = {
                x: d.x || 0,
                y: d.y || 0,
                z: d.z || 0
            });
            d = null;
            const f = b.rotationRate;
            f && (d = {
                alpha: f.alpha || 0,
                beta: f.beta || 0,
                gamma: f.gamma || 0
            });
            this.PostToRuntime("devicemotion", {
                acceleration: c,
                accelerationIncludingGravity: a,
                rotationRate: d,
                interval: b.interval,
                timeStamp: b.timeStamp
            }, l)
        }
        _OnUpdateCanvasSize(b) {
            const c = this.GetRuntimeInterface().GetCanvas();
            c.style.width = b.styleWidth + "px";
            c.style.height = b.styleHeight + "px";
            c.style.marginLeft = b.marginLeft + "px";
            c.style.marginTop = b.marginTop + "px";
            this._isFirstSizeUpdate && (c.style.display =
                "", this._isFirstSizeUpdate = !1)
        }
        _OnInvokeDownload(b) {
            const c = b.url;
            b = b.filename;
            const a = document.createElement("a"),
                d = document.body;
            a.textContent = b;
            a.href = c;
            a.download = b;
            d.appendChild(a);
            a.click();
            d.removeChild(a)
        }
        async _OnRasterSvgImage(b) {
            var c = b.imageBitmapOpts;
            b = await self.C3_RasterSvgImageBlob(b.blob, b.imageWidth, b.imageHeight, b.surfaceWidth, b.surfaceHeight);
            c = c ? await createImageBitmap(b, c) : await createImageBitmap(b);
            return {
                imageBitmap: c,
                transferables: [c]
            }
        }
        async _OnGetSvgImageSize(b) {
            return await self.C3_GetSvgImageSize(b.blob)
        }
        async _OnAddStylesheet(b) {
            await w(b.url)
        }
        _PlayPendingMedia() {
            var b = [...this._mediaPendingPlay];
            this._mediaPendingPlay.clear();
            if (!this._isSilent)
                for (const c of b)(b = c.play()) && b.catch(a => {
                    this._mediaRemovedPendingPlay.has(c) || this._mediaPendingPlay.add(c)
                })
        }
        TryPlayMedia(b) {
            if ("function" !== typeof b.play) throw Error("missing play function");
            this._mediaRemovedPendingPlay.delete(b);
            let c;
            try {
                c = b.play()
            } catch (a) {
                this._mediaPendingPlay.add(b);
                return
            }
            c && c.catch(a => {
                this._mediaRemovedPendingPlay.has(b) || this._mediaPendingPlay.add(b)
            })
        }
        RemovePendingPlay(b) {
            this._mediaPendingPlay.delete(b);
            this._mediaRemovedPendingPlay.add(b)
        }
        SetSilent(b) {
            this._isSilent = !!b
        }
        _OnHideCordovaSplash() {
            navigator.splashscreen && navigator.splashscreen.hide && navigator.splashscreen.hide()
        }
        _OnDebugHighlight(b) {
            if (b.show) {
                this._debugHighlightElem || (this._debugHighlightElem = document.createElement("div"), this._debugHighlightElem.id = "inspectOutline", document.body.appendChild(this._debugHighlightElem));
                var c = this._debugHighlightElem;
                c.style.display = "";
                c.style.left = b.left - 1 + "px";
                c.style.top = b.top - 1 + "px";
                c.style.width =
                    b.width + 2 + "px";
                c.style.height = b.height + 2 + "px";
                c.textContent = b.name
            } else this._debugHighlightElem && (this._debugHighlightElem.style.display = "none")
        }
        _OnRegisterSW() {
            window.C3_RegisterSW && window.C3_RegisterSW()
        }
        _OnPostToDebugger(b) {
            window.c3_postToMessagePort && (b.from = "runtime", window.c3_postToMessagePort(b))
        }
        _InvokeFunctionFromJS(b, c) {
            return this.PostToRuntimeAsync("js-invoke-function", {
                name: b,
                params: c
            })
        }
        _OnAlert(b) {
            alert(b.message)
        }
        _OnWrapperMessage(b) {
            "entered-fullscreen" === b ? (e._SetWrapperIsFullscreenFlag(!0),
                this._OnFullscreenChange()) : "exited-fullscreen" === b ? (e._SetWrapperIsFullscreenFlag(!1), this._OnFullscreenChange()) : console.warn("Unknown wrapper message: ", b)
        }
    })
}
"use strict";
self.JobSchedulerDOM = class {
    constructor(e) {
        this._runtimeInterface = e;
        this._baseUrl = e.GetBaseURL();
        "preview" === e.GetExportType() ? this._baseUrl += "workers/" : this._baseUrl += e.GetScriptFolder();
        this._maxNumWorkers = Math.min(navigator.hardwareConcurrency || 2, 16);
        this._dispatchWorker = null;
        this._jobWorkers = [];
        this._outputPort = this._inputPort = null
    }
    async Init() {
        if (this._hasInitialised) throw Error("already initialised");
        this._hasInitialised = !0;
        var e = this._runtimeInterface._GetWorkerURL("dispatchworker.js");
        this._dispatchWorker =
            await this._runtimeInterface.CreateWorker(e, this._baseUrl, {
                name: "DispatchWorker"
            });
        e = new MessageChannel;
        this._inputPort = e.port1;
        this._dispatchWorker.postMessage({
            type: "_init",
            "in-port": e.port2
        }, [e.port2]);
        this._outputPort = await this._CreateJobWorker()
    }
    async _CreateJobWorker() {
        const e = this._jobWorkers.length;
        var h = this._runtimeInterface._GetWorkerURL("jobworker.js");
        h = await this._runtimeInterface.CreateWorker(h, this._baseUrl, {
            name: "JobWorker" + e
        });
        const g = new MessageChannel,
            l = new MessageChannel;
        this._dispatchWorker.postMessage({
            type: "_addJobWorker",
            port: g.port1
        }, [g.port1]);
        h.postMessage({
            type: "init",
            number: e,
            "dispatch-port": g.port2,
            "output-port": l.port2
        }, [g.port2, l.port2]);
        this._jobWorkers.push(h);
        return l.port1
    }
    GetPortData() {
        return {
            inputPort: this._inputPort,
            outputPort: this._outputPort,
            maxNumWorkers: this._maxNumWorkers
        }
    }
    GetPortTransferables() {
        return [this._inputPort, this._outputPort]
    }
};
"use strict";
window.C3_IsSupported && (window.c3_runtimeInterface = new self.RuntimeInterface({
    useWorker: !0,
    workerMainUrl: "workermain.js",
    engineScripts: ["scripts/c3runtime.js"],
    projectScripts: [],
    mainProjectScript: "",
    scriptFolder: "scripts/",
    workerDependencyScripts: [],
    exportType: "html5"
}));
"use strict"; {
    function e(g) {
        g.stopPropagation()
    }

    function h(g) {
        13 !== g.which && 27 !== g.which && g.stopPropagation()
    }
    self.RuntimeInterface.AddDOMHandlerClass(class extends self.DOMElementHandler {
        constructor(g) {
            super(g, "text-input");
            this.AddDOMElementMessageHandler("scroll-to-bottom", l => this._OnScrollToBottom(l))
        }
        CreateElement(g, l) {
            let q;
            const v = l.type;
            "textarea" === v ? (q = document.createElement("textarea"), q.style.resize = "none") : (q = document.createElement("input"), q.type = v);
            q.style.position = "absolute";
            q.autocomplete = "off";
            q.addEventListener("touchstart", e);
            q.addEventListener("touchmove", e);
            q.addEventListener("touchend", e);
            q.addEventListener("mousedown", e);
            q.addEventListener("mouseup", e);
            q.addEventListener("keydown", h);
            q.addEventListener("keyup", h);
            q.addEventListener("click", w => {
                w.stopPropagation();
                this._PostToRuntimeElementMaybeSync("click", g)
            });
            q.addEventListener("dblclick", w => {
                w.stopPropagation();
                this._PostToRuntimeElementMaybeSync("dblclick", g)
            });
            q.addEventListener("input", () => this.PostToRuntimeElement("change",
                g, {
                    text: q.value
                }));
            l.id && (q.id = l.id);
            this.UpdateState(q, l);
            return q
        }
        UpdateState(g, l) {
            g.value = l.text;
            g.placeholder = l.placeholder;
            g.title = l.title;
            g.disabled = !l.isEnabled;
            g.readOnly = l.isReadOnly;
            g.spellcheck = l.spellCheck;
            l = l.maxLength;
            0 > l ? g.removeAttribute("maxlength") : g.setAttribute("maxlength", l)
        }
        _OnScrollToBottom(g) {
            g.scrollTop = g.scrollHeight
        }
    })
}
"use strict";
self.RuntimeInterface.AddDOMHandlerClass(class extends self.DOMHandler {
    constructor(e) {
        super(e, "touch");
        this.AddRuntimeMessageHandler("request-permission", h => this._OnRequestPermission(h))
    }
    async _OnRequestPermission(e) {
        e = e.type;
        let h = !0;
        0 === e ? h = await this._RequestOrientationPermission() : 1 === e && (h = await this._RequestMotionPermission());
        this.PostToRuntime("permission-result", {
            type: e,
            result: h
        })
    }
    async _RequestOrientationPermission() {
        if (!self.DeviceOrientationEvent || !self.DeviceOrientationEvent.requestPermission) return !0;
        try {
            return "granted" === await self.DeviceOrientationEvent.requestPermission()
        } catch (e) {
            return console.warn("[Touch] Failed to request orientation permission: ", e), !1
        }
    }
    async _RequestMotionPermission() {
        if (!self.DeviceMotionEvent || !self.DeviceMotionEvent.requestPermission) return !0;
        try {
            return "granted" === await self.DeviceMotionEvent.requestPermission()
        } catch (e) {
            return console.warn("[Touch] Failed to request motion permission: ", e), !1
        }
    }
});
"use strict";
self.RuntimeInterface.AddDOMHandlerClass(class extends self.DOMHandler {
    constructor(e) {
        super(e, "mouse");
        this.AddRuntimeMessageHandlers([
            ["cursor", h => this._OnChangeCursorStyle(h)],
            ["request-pointer-lock", () => this._OnRequestPointerLock()],
            ["release-pointer-lock", () => this._OnReleasePointerLock()]
        ]);
        document.addEventListener("pointerlockchange", h => this._OnPointerLockChange());
        document.addEventListener("pointerlockerror", h => this._OnPointerLockError())
    }
    _OnChangeCursorStyle(e) {
        document.documentElement.style.cursor =
            e
    }
    _OnRequestPointerLock() {
        this._iRuntime.GetCanvas().requestPointerLock()
    }
    _OnReleasePointerLock() {
        document.exitPointerLock()
    }
    _OnPointerLockChange() {
        this.PostToRuntime("pointer-lock-change", {
            "has-pointer-lock": !!document.pointerLockElement
        })
    }
    _OnPointerLockError() {
        this.PostToRuntime("pointer-lock-error", {
            "has-pointer-lock": !!document.pointerLockElement
        })
    }
});
"use strict";
self.RuntimeInterface.AddDOMHandlerClass(class extends self.DOMHandler {
    constructor(e) {
        super(e, "platform-info");
        this.AddRuntimeMessageHandlers([
            ["get-initial-state", () => this._OnGetInitialState()],
            ["request-wake-lock", () => this._OnRequestWakeLock()],
            ["release-wake-lock", () => this._OnReleaseWakeLock()]
        ]);
        window.addEventListener("resize", () => this._OnResize());
        this._screenWakeLock = null
    }
    _OnGetInitialState() {
        return {
            screenWidth: screen.width,
            screenHeight: screen.height,
            windowOuterWidth: window.outerWidth,
            windowOuterHeight: window.outerHeight,
            safeAreaInset: this._GetSafeAreaInset(),
            supportsWakeLock: !!navigator.wakeLock
        }
    }
    _GetSafeAreaInset() {
        var e = document.body;
        const h = e.style;
        h.setProperty("--temp-sai-top", "env(safe-area-inset-top)");
        h.setProperty("--temp-sai-right", "env(safe-area-inset-right)");
        h.setProperty("--temp-sai-bottom", "env(safe-area-inset-bottom)");
        h.setProperty("--temp-sai-left", "env(safe-area-inset-left)");
        e = getComputedStyle(e);
        e = [e.getPropertyValue("--temp-sai-top"), e.getPropertyValue("--temp-sai-right"), e.getPropertyValue("--temp-sai-bottom"),
            e.getPropertyValue("--temp-sai-left")
        ].map(g => {
            g = parseInt(g, 10);
            return isFinite(g) ? g : 0
        });
        h.removeProperty("--temp-sai-top");
        h.removeProperty("--temp-sai-right");
        h.removeProperty("--temp-sai-bottom");
        h.removeProperty("--temp-sai-left");
        return e
    }
    _OnResize() {
        this.PostToRuntime("window-resize", {
            windowOuterWidth: window.outerWidth,
            windowOuterHeight: window.outerHeight,
            safeAreaInset: this._GetSafeAreaInset()
        })
    }
    async _OnRequestWakeLock() {
        if (!this._screenWakeLock) try {
            this._screenWakeLock = await navigator.wakeLock.request("screen"),
                this._screenWakeLock.addEventListener("release", () => this._OnWakeLockReleased()), console.log("[Construct 3] Screen wake lock acquired"), this.PostToRuntime("wake-lock-acquired")
        } catch (e) {
            console.warn("[Construct 3] Failed to acquire screen wake lock: ", e), this.PostToRuntime("wake-lock-error")
        }
    }
    _OnReleaseWakeLock() {
        this._screenWakeLock && (this._screenWakeLock.release(), this._screenWakeLock = null)
    }
    _OnWakeLockReleased() {
        console.log("[Construct 3] Screen wake lock released");
        this._screenWakeLock = null;
        this.PostToRuntime("wake-lock-released")
    }
});