(function () {
    var o = !! !document.createElement("canvas").getContext;
    var s = !! document.createElement("canvas").getContext;
    var d = !! window.SVGAngle || document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1");
    var a = !(s | d);
    var l = function (y, z, w, C) {
            var B = function (F, E) {
                    if (F === E) {
                        return true
                    } else {
                        if (typeof F == "object" && typeof E == "object") {
                            var G = true;
                            for (var D in F) {
                                if (!B(F[D], E[D])) {
                                    G = false;
                                    break
                                }
                            }
                            for (var D in E) {
                                if (!B(E[D], F[D])) {
                                    G = false;
                                    break
                                }
                            }
                            return G
                        }
                    }
                };
            for (var A = +w || 0, x = y.length; A < x; A++) {
                if (B(y[A], z)) {
                    return A
                }
            }
            return -1
        };
    var g = function (z, x, y) {
            var w = z[x];
            if (w == null) {
                w = [];
                z[x] = w
            }
            w.push(y);
            return w
        };
    var q = null;
    var c = function (w, x) {
            return i.CurrentLibrary.getAttribute(v(w), x)
        };
    var e = function (x, y, w) {
            i.CurrentLibrary.setAttribute(v(x), y, w)
        };
    var t = function (x, w) {
            i.CurrentLibrary.addClass(v(x), w)
        };
    var f = function (x, w) {
            return i.CurrentLibrary.hasClass(v(x), w)
        };
    var h = function (x, w) {
            i.CurrentLibrary.removeClass(v(x), w)
        };
    var v = function (w) {
            return i.CurrentLibrary.getElementObject(w)
        };
    var n = function (w) {
            return i.CurrentLibrary.getOffset(v(w))
        };
    var b = function (w) {
            return i.CurrentLibrary.getSize(v(w))
        };
    var k = function (w, x) {
            if (w.logEnabled && typeof console != "undefined") {
                console.log(x)
            }
        };
    var p = function () {
            var y = {},
                x = this;
            var w = ["ready"];
            this.bind = function (z, A) {
                g(y, z, A)
            };
            this.fire = function (B, C, z) {
                if (y[B]) {
                    for (var A = 0; A < y[B].length; A++) {
                        if (l(w, B) != -1) {
                            y[B][A](C, z)
                        } else {
                            try {
                                y[B][A](C, z)
                            } catch (D) {
                                k("jsPlumb: fire failed for event " + B + " : " + D)
                            }
                        }
                    }
                }
            };
            this.clearListeners = function (z) {
                if (z) {
                    delete y[z]
                } else {
                    delete y;
                    y = {}
                }
            }
        };
    var u = function (A) {
            var y = this,
                x = arguments,
                w = false;
            y._jsPlumb = A._jsPlumb;
            p.apply(this);
            this.clone = function () {
                var C = new Object();
                y.constructor.apply(C, x);
                return C
            };
            this.overlayPlacements = [], this.paintStyle = null, this.hoverPaintStyle = null;
            var B = function () {
                    if (y.paintStyle && y.hoverPaintStyle) {
                        var C = {};
                        i.extend(C, y.paintStyle);
                        i.extend(C, y.hoverPaintStyle);
                        delete y.hoverPaintStyle;
                        if (C.gradient && y.paintStyle.fillStyle) {
                            delete C.gradient
                        }
                        y.hoverPaintStyle = C
                    }
                };
            this.setPaintStyle = function (C, D) {
                y.paintStyle = C;
                y.paintStyleInUse = y.paintStyle;
                B();
                if (!D) {
                    y.repaint()
                }
            };
            this.setHoverPaintStyle = function (C, D) {
                y.hoverPaintStyle = C;
                B();
                if (!D) {
                    y.repaint()
                }
            };
            this.setHover = function (C, D) {
                w = C;
                if (y.hoverPaintStyle != null) {
                    y.paintStyleInUse = C ? y.hoverPaintStyle : y.paintStyle;
                    y.repaint();
                    if (!D) {
                        z(C)
                    }
                }
            };
            this.isHover = function () {
                return w
            };
            this.attachListeners = function (H, I) {
                var F = i.CurrentLibrary,
                    D = ["click", "dblclick", "mouseenter", "mouseout", "mousemove", "mousedown", "mouseup"],
                    G = {
                        mouseout: "mouseexit"
                    },
                    E = function (K) {
                        var J = G[K] || K;
                        F.bind(H, K, function (L) {
                            I.fire(J, I, L)
                        })
                    };
                for (var C = 0; C < D.length; C++) {
                    E(D[C])
                }
            };
            var z = function (E) {
                    var D = y.getAttachedElements();
                    if (D) {
                        for (var C = 0; C < D.length; C++) {
                            D[C].setHover(E, true)
                        }
                    }
                }
        };
    var r = function (B) {
            this.Defaults = {
                Anchor: "BottomCenter",
                Anchors: [null, null],
                Connector: "Bezier",
                DragOptions: {},
                DropOptions: {},
                Endpoint: "Dot",
                Endpoints: [null, null],
                EndpointStyle: {
                    fillStyle: null
                },
                EndpointStyles: [null, null],
                EndpointHoverStyle: null,
                EndpointHoverStyles: [null, null],
                HoverPaintStyle: null,
                LabelStyle: {
                    color: "black"
                },
                LogEnabled: false,
                Overlays: [],
                MaxConnections: 1,
                MouseEventsEnabled: true,
                PaintStyle: {
                    lineWidth: 8,
                    strokeStyle: "#456"
                },
                RenderMode: "canvas",
                Scope: "_jsPlumb_DefaultScope"
            };
            if (B) {
                i.extend(this.Defaults, B)
            }
            this.logEnabled = this.Defaults.LogEnabled;
            p.apply(this);
            var av = this.bind;
            this.bind = function (ay, ax) {
                if ("ready" === ay && K) {
                    ax()
                } else {
                    av(ay, ax)
                }
            };
            var E = this,
                G = null,
                ak = function () {
                    i.repaintEverything()
                },
                z = true,
                aj = function () {
                    if (z) {
                        ak()
                    }
                },
                ai = null,
                K = false,
                M = {},
                ah = {},
                U = {},
                W = {},
                D = {},
                Z = {},
                ag = {},
                ap = this.Defaults.MouseEventsEnabled,
                Y = true,
                af = [],
                Q = [],
                al = {},
                C = this.Defaults.Scope,
                aa = null,
                T = function (aA, ay, az) {
                    var ax = aA[ay];
                    if (ax == null) {
                        ax = [];
                        aA[ay] = ax
                    }
                    ax.push(az);
                    return ax
                },
                S = function (ay, ax) {
                    if (!ax) {
                        document.body.appendChild(ay)
                    } else {
                        i.CurrentLibrary.appendElement(ay, ax)
                    }
                },
                ar = function () {
                    return "" + (new Date()).getTime()
                },
                x = function (ax) {
                    return ax._nodes ? ax._nodes : ax
                },
                at = function (aC, aJ, aF) {
                    var ax = c(aC, "id");
                    var ay = ah[ax];
                    if (!aF) {
                        aF = ar()
                    }
                    if (ay) {
                        F({
                            elId: ax,
                            offset: aJ,
                            recalc: false,
                            timestamp: aF
                        });
                        var aG = W[ax],
                            aE = Q[ax];
                        for (var aD = 0; aD < ay.length; aD++) {
                            ay[aD].paint({
                                timestamp: aF,
                                offset: aG,
                                dimensions: aE
                            });
                            var az = ay[aD].connections;
                            for (var aB = 0; aB < az.length; aB++) {
                                az[aB].paint({
                                    elId: ax,
                                    ui: aJ,
                                    recalc: false,
                                    timestamp: aF
                                });
                                var aL = az[aB].endpoints[0] == ay[aD] ? 1 : 0;
                                if (az[aB].endpoints[aL].anchor.isDynamic && !az[aB].endpoints[aL].isFloating()) {
                                    var aA = aL == 0 ? az[aB].sourceId : az[aB].targetId,
                                        aH = W[aA],
                                        aI = Q[aA],
                                        aK = az[aB].endpoints[aL].anchor.compute({
                                            xy: [aH.left, aH.top],
                                            wh: aI,
                                            element: az[aB].endpoints[aL],
                                            txy: [aG.left, aG.top],
                                            twh: aE,
                                            tElement: ay[aD]
                                        });
                                    az[aB].endpoints[aL].paint({
                                        anchorLoc: aK
                                    })
                                }
                            }
                        }
                    }
                },
                J = function (ay, aA) {
                    var aB = null;
                    if (ay.constructor == Array) {
                        aB = [];
                        for (var ax = 0; ax < ay.length; ax++) {
                            var az = v(ay[ax]),
                                aC = c(az, "id");
                            aB.push(aA(az, aC))
                        }
                    } else {
                        var az = v(ay),
                            aC = c(az, "id");
                        aB = aA(az, aC)
                    }
                    return aB
                },
                X = function (ax) {
                    return U[ax]
                },
                P = function (aB, aA, az) {
                    var ax = aA == null ? Y : aA;
                    if (ax) {
                        if (i.CurrentLibrary.isDragSupported(aB) && !i.CurrentLibrary.isAlreadyDraggable(aB)) {
                            var ay = az || E.Defaults.DragOptions || i.Defaults.DragOptions;
                            ay = i.extend({}, ay);
                            var aD = i.CurrentLibrary.dragEvents.drag;
                            var aC = i.CurrentLibrary.dragEvents.stop;
                            ay[aD] = am(ay[aD], function () {
                                var aE = i.CurrentLibrary.getUIPosition(arguments);
                                at(aB, aE);
                                t(aB, "jsPlumb_dragged")
                            });
                            ay[aC] = am(ay[aC], function () {
                                var aE = i.CurrentLibrary.getUIPosition(arguments);
                                at(aB, aE);
                                h(aB, "jsPlumb_dragged")
                            });
                            var ax = ag[aw(aB)];
                            ay.disabled = ax == null ? false : !ax;
                            i.CurrentLibrary.initDraggable(aB, ay)
                        }
                    }
                },
                au = function (aB) {
                    var aA = i.Defaults.ConnectionType || H,
                        az = i.Defaults.EndpointType || aq,
                        ay = i.CurrentLibrary.getParent;
                    if (aB.sourceEndpoint) {
                        aB.parent = aB.sourceEndpoint.parent
                    } else {
                        if (aB.source.constructor == az) {
                            aB.parent = aB.source.parent
                        } else {
                            aB.parent = ay(aB.source)
                        }
                    }
                    aB._jsPlumb = E;
                    var ax = new aA(aB);
                    A("click", "click", ax);
                    A("dblclick", "dblclick", ax);
                    return ax
                },
                A = function (ax, ay, az) {
                    az.bind(ax, function (aA) {
                        E.fire(ay, az, aA)
                    })
                },
                an = function (ay) {
                    var ax = i.Defaults.EndpointType || aq;
                    ay.parent = i.CurrentLibrary.getParent(ay.source);
                    ay._jsPlumb = E, ep = new ax(ay);
                    A("click", "endpointClick", ep);
                    A("dblclick", "endpointDblClick", ep);
                    return ep
                },
                V = function (az, aB) {
                    var ax = ah[az];
                    if (ax && ax.length) {
                        for (var aA = 0; aA < ax.length; aA++) {
                            for (var ay = 0; ay < ax[aA].connections.length; ay++) {
                                var aC = aB(ax[aA].connections[ay]);
                                if (aC) {
                                    return
                                }
                            }
                        }
                    }
                },
                L = function (ay) {
                    for (var ax in ah) {
                        V(ax, ay)
                    }
                },
                ae = function (ax, ay) {
                    if (ax != null && ax.parentNode != null) {
                        ax.parentNode.removeChild(ax)
                    }
                },
                R = function (az, ay) {
                    for (var ax = 0; ax < az.length; ax++) {
                        ae(az[ax], ay)
                    }
                },
                N = function (aB, az, aA) {
                    if (az != null) {
                        var ax = aB[az];
                        if (ax != null) {
                            var ay = l(ax, aA);
                            if (ay >= 0) {
                                delete(ax[ay]);
                                ax.splice(ay, 1);
                                return true
                            }
                        }
                    }
                    return false
                },
                w = function (ay, ax) {
                    return J(ay, function (az, aA) {
                        ag[aA] = ax;
                        if (i.CurrentLibrary.isDragSupported(az)) {
                            i.CurrentLibrary.setDraggable(az, ax)
                        }
                    })
                },
                ao = function (ax, ay) {
                    V(c(ax, "id"), function (az) {
                        az.canvas.style.display = ay
                    })
                },
                O = function (ax) {
                    return J(ax, function (az, ay) {
                        var aA = ag[ay] == null ? Y : ag[ay];
                        aA = !aA;
                        ag[ay] = aA;
                        i.CurrentLibrary.setDraggable(az, aA);
                        return aA
                    })
                },
                y = function (ax) {
                    V(ax, function (az) {
                        var ay = ("none" == az.canvas.style.display);
                        az.canvas.style.display = ay ? "block" : "none"
                    })
                },
                F = function (aC) {
                    var aA = aC.timestamp,
                        ax = aC.recalc,
                        aB = aC.offset,
                        ay = aC.elId;
                    if (!ax) {
                        if (aA && aA === D[ay]) {
                            return W[ay]
                        }
                    }
                    if (ax || aB == null) {
                        var az = v(ay);
                        if (az != null) {
                            Q[ay] = b(az);
                            W[ay] = n(az);
                            D[ay] = aA
                        }
                    } else {
                        W[ay] = aB
                    }
                    return W[ay]
                },
                aw = function (ax, ay) {
                    var az = v(ax);
                    var aA = c(az, "id");
                    if (!aA || aA == "undefined") {
                        if (arguments.length == 2 && arguments[1] != undefined) {
                            aA = ay
                        } else {
                            aA = "jsPlumb_connection"
                        }
                        e(az, "id", aA)
						e(az, "name", aA)
                    }
                    return aA
                },
                am = function (az, ax, ay) {
                    az = az ||
                    function () {};
                    ax = ax ||
                    function () {};
                    return function () {
                        var aA = null;
                        try {
                            aA = ax.apply(this, arguments)
                        } catch (aB) {
                            k(E, "jsPlumb function failed : " + aB)
                        }
                        if (ay == null || (aA !== ay)) {
                            try {
                                az.apply(this, arguments)
                            } catch (aB) {
                                k(E, "wrapped function failed : " + aB)
                            }
                        }
                        return aA
                    }
                };
            this.connectorClass = "_jsPlumb_connector";
            this.endpointClass = "_jsPlumb_endpoint";
            this.overlayClass = "_jsPlumb_overlay";
            this.Anchors = {};
            this.Connectors = {
                canvas: {},
                svg: {},
                vml: {}
            };
            this.Endpoints = {
                canvas: {},
                svg: {},
                vml: {}
            };
            this.Overlays = {
                canvas: {},
                svg: {},
                vml: {}
            };
            this.addEndpoint = function (az, aA, aJ) {
                aJ = aJ || {};
                var ay = i.extend({}, aJ);
                i.extend(ay, aA);
                ay.endpoint = ay.endpoint || E.Defaults.Endpoint || i.Defaults.Endpoint;
                ay.paintStyle = ay.paintStyle || E.Defaults.EndpointStyle || i.Defaults.EndpointStyle;
                az = x(az);
                var aB = [],
                    aE = az.length && az.constructor != String ? az : [az];
                for (var aC = 0; aC < aE.length; aC++) {
                    var aH = v(aE[aC]),
                        ax = aw(aH);
                    ay.source = aH;
                    F({
                        elId: ax
                    });
                    var aG = an(ay);
                    T(ah, ax, aG);
                    var aF = W[ax],
                        aD = Q[ax];
                    var aI = aG.anchor.compute({
                        xy: [aF.left, aF.top],
                        wh: aD,
                        element: aG
                    });
                    aG.paint({
                        anchorLoc: aI
                    });
                    aB.push(aG)
                }
                return aB.length == 1 ? aB[0] : aB
            };
            this.addEndpoints = function (aB, ay, ax) {
                var aA = [];
                for (var az = 0; az < ay.length; az++) {
                    var aC = E.addEndpoint(aB, ay[az], ax);
                    if (aC.constructor == Array) {
                        Array.prototype.push.apply(aA, aC)
                    } else {
                        aA.push(aC)
                    }
                }
                return aA
            };
            this.animate = function (az, ay, ax) {
                var aA = v(az),
                    aD = c(az, "id");
                ax = ax || {};
                var aC = i.CurrentLibrary.dragEvents.step;
                var aB = i.CurrentLibrary.dragEvents.complete;
                ax[aC] = am(ax[aC], function () {
                    E.repaint(aD)
                });
                ax[aB] = am(ax[aB], function () {
                    E.repaint(aD)
                });
                i.CurrentLibrary.animate(aA, ay, ax)
            };
            this.connect = function (aD, az) {
                var ay = i.extend({}, aD);
                if (az) {
                    i.extend(ay, az)
                }
                if (ay.source && ay.source.endpoint) {
                    ay.sourceEndpoint = ay.source
                }
                if (ay.source && ay.target.endpoint) {
                    ay.targetEndpoint = ay.target
                }
                if (aD.uuids) {
                    ay.sourceEndpoint = X(aD.uuids[0]);
                    ay.targetEndpoint = X(aD.uuids[1])
                }
                if (ay.sourceEndpoint && ay.sourceEndpoint.isFull()) {
                    k(E, "could not add connection; source endpoint is full");
                    return
                }
                if (ay.targetEndpoint && ay.targetEndpoint.isFull()) {
                    k(E, "could not add connection; target endpoint is full");
                    return
                }
                if (ay.dynamicAnchors) {
                    var aA = ay.dynamicAnchors.constructor == Array;
                    var ax = aA ? new ab(i.makeAnchors(ay.dynamicAnchors)) : new ab(i.makeAnchors(ay.dynamicAnchors.source));
                    var aB = aA ? new ab(i.makeAnchors(ay.dynamicAnchors)) : new ab(i.makeAnchors(ay.dynamicAnchors.target));
                    ay.anchors = [ax, aB]
                }
                var aC = au(ay);
                T(M, aC.scope, aC);
                E.fire("jsPlumbConnection", {
                    connection: aC,
                    source: aC.source,
                    target: aC.target,
                    sourceId: aC.sourceId,
                    targetId: aC.targetId,
                    sourceEndpoint: aC.endpoints[0],
                    targetEndpoint: aC.endpoints[1]
                });
                at(aC.source);
                return aC
            };
            this.deleteEndpoint = function (ay) {
                var aD = (typeof ay == "string") ? U[ay] : ay;
                if (aD) {
                    var aA = aD.getUuid();
                    if (aA) {
                        U[aA] = null
                    }
                    aD.detachAll();
                    ae(aD.canvas, aD.parent);
                    for (var aC in ah) {
                        var ax = ah[aC];
                        if (ax) {
                            var aB = [];
                            for (var az = 0; az < ax.length; az++) {
                                if (ax[az] != aD) {
                                    aB.push(ax[az])
                                }
                            }
                            ah[aC] = aB
                        }
                    }
                    delete aD
                }
            };
            this.deleteEveryEndpoint = function () {
                for (var az in ah) {
                    var ax = ah[az];
                    if (ax && ax.length) {
                        for (var ay = 0; ay < ax.length; ay++) {
                            E.deleteEndpoint(ax[ay])
                        }
                    }
                }
                delete ah;
                ah = {};
                delete U;
                U = {}
            };
            var ad = function (ax) {
                    E.fire("jsPlumbConnectionDetached", {
                        connection: ax,
                        source: ax.source,
                        target: ax.target,
                        sourceId: ax.sourceId,
                        targetId: ax.targetId,
                        sourceEndpoint: ax.endpoints[0],
                        targetEndpoint: ax.endpoints[1]
                    })
                };
            this.detach = function (ax, aB) {
                if (arguments.length == 2) {
                    var aF = v(ax),
                        az = aw(aF);
                    var aE = v(aB),
                        aA = aw(aE);
                    V(az, function (aG) {
                        if ((aG.sourceId == az && aG.targetId == aA) || (aG.targetId == az && aG.sourceId == aA)) {
                            R(aG.connector.getDisplayElements(), aG.parent);
                            aG.endpoints[0].removeConnection(aG);
                            aG.endpoints[1].removeConnection(aG);
                            N(M, aG.scope, aG)
                        }
                    })
                } else {
                    if (arguments.length == 1) {
                        if (arguments[0].constructor == H) {
                            arguments[0].endpoints[0].detachFrom(arguments[0].endpoints[1])
                        } else {
                            if (arguments[0].connection) {
                                arguments[0].connection.endpoints[0].detachFrom(arguments[0].connection.endpoints[1])
                            } else {
                                var ay = i.extend({}, ax);
                                if (ay.uuids) {
                                    X(ay.uuids[0]).detachFrom(X(ay.uuids[1]))
                                } else {
                                    if (ay.sourceEndpoint && ay.targetEndpoint) {
                                        ay.sourceEndpoint.detachFrom(ay.targetEndpoint)
                                    } else {
                                        var aD = aw(ay.source);
                                        var aC = aw(ay.target);
                                        V(aD, function (aG) {
                                            if ((aG.sourceId == aD && aG.targetId == aC) || (aG.targetId == aD && aG.sourceId == aC)) {
                                                R(aG.connector.getDisplayElements(), aG.parent);
                                                aG.endpoints[0].removeConnection(aG);
                                                aG.endpoints[1].removeConnection(aG);
                                                N(M, aG.scope, aG)
                                            }
                                        })
                                    }
                                }
                            }
                        }
                    }
                }
            };
            this.detachAllConnections = function (az) {
                var aA = c(az, "id");
                var ax = ah[aA];
                if (ax && ax.length) {
                    for (var ay = 0; ay < ax.length; ay++) {
                        ax[ay].detachAll()
                    }
                }
            };
            this.detachAll = this.detachAllConnections;
            this.detachEveryConnection = function () {
                for (var az in ah) {
                    var ax = ah[az];
                    if (ax && ax.length) {
                        for (var ay = 0; ay < ax.length; ay++) {
                            ax[ay].detachAll()
                        }
                    }
                }
                delete M;
                M = {}
            };
            this.detachEverything = this.detachEveryConnection;
            this.draggable = function (az, ax) {
                if (typeof az == "object" && az.length) {
                    for (var ay = 0; ay < az.length; ay++) {
                        var aA = v(az[ay]);
                        if (aA) {
                            P(aA, true, ax)
                        }
                    }
                } else {
                    if (az._nodes) {
                        for (var ay = 0; ay < az._nodes.length; ay++) {
                            var aA = v(az._nodes[ay]);
                            if (aA) {
                                P(aA, true, ax)
                            }
                        }
                    } else {
                        var aA = v(az);
                        if (aA) {
                            P(aA, true, ax)
                        }
                    }
                }
            };
            this.extend = function (ay, ax) {
                return i.CurrentLibrary.extend(ay, ax)
            };
            this.getDefaultEndpointType = function () {
                return aq
            };
            this.getDefaultConnectionType = function () {
                return H
            };
            this.getConnections = function (aI) {
                if (!aI) {
                    aI = {}
                } else {
                    if (aI.constructor == String) {
                        aI = {
                            scope: aI
                        }
                    }
                }
                var aF = function (aJ) {
                        var aK = [];
                        if (aJ) {
                            if (typeof aJ == "string") {
                                aK.push(aJ)
                            } else {
                                aK = aJ
                            }
                        }
                        return aK
                    };
                var aG = aI.scope || i.getDefaultScope(),
                    aE = aF(aG),
                    ax = aF(aI.source),
                    aC = aF(aI.target),
                    ay = function (aK, aJ) {
                        return aK.length > 0 ? l(aK, aJ) != -1 : true
                    },
                    aB = aE.length > 1 ? {} : [],
                    aH = function (aK, aL) {
                        if (aE.length > 1) {
                            var aJ = aB[aK];
                            if (aJ == null) {
                                aJ = [];
                                aB[aK] = aJ
                            }
                            aJ.push(aL)
                        } else {
                            aB.push(aL)
                        }
                    };
                for (var aA in M) {
                    if (ay(aE, aA)) {
                        for (var az = 0; az < M[aA].length; az++) {
                            var aD = M[aA][az];
                            if (ay(ax, aD.sourceId) && ay(aC, aD.targetId)) {
                                aH(aA, aD)
                            }
                        }
                    }
                }
                return aB
            };
            this.getAllConnections = function () {
                return M
            };
            this.getDefaultScope = function () {
                return C
            };
            this.getEndpoint = X;
            this.getEndpoints = function (ax) {
                return ah[aw(ax)]
            };
            this.getId = aw;
            this.appendElement = S;
            this.hide = function (ax) {
                ao(ax, "none")
            };
            this.init = function () {
                if (!K) {
                    E.setRenderMode(E.Defaults.RenderMode);
                    var ax = function (ay) {
                            i.CurrentLibrary.bind(document, ay, function (aE) {
                                if (!E.currentlyDragging && ap && aa == i.CANVAS) {
                                    for (var aD in M) {
                                        var aF = M[aD];
                                        for (var aB = 0; aB < aF.length; aB++) {
                                            var aA = aF[aB].connector[ay](aE);
                                            if (aA) {
                                                return
                                            }
                                        }
                                    }
                                    for (var aC in ah) {
                                        var az = ah[aC];
                                        for (var aB = 0; aB < az.length; aB++) {
                                            if (az[aB].endpoint[ay](aE)) {
                                                return
                                            }
                                        }
                                    }
                                }
                            })
                        };
                    ax("click");
                    ax("dblclick");
                    ax("mousemove");
                    ax("mousedown");
                    ax("mouseup");
                    K = true;
                    E.fire("ready")
                }
            };
            this.jsPlumbUIComponent = u;
            this.EventGenerator = p;
            this.makeAnchor = function (aE, aB, aC, az, aA, ax) {
                if (arguments.length == 0) {
                    return null
                }
                var ay = {};
                if (arguments.length == 1) {
                    var aF = arguments[0];
                    if (aF.compute && aF.getOrientation) {
                        return aF
                    } else {
                        if (typeof aF == "string") {
                            return E.Anchors[arguments[0]]()
                        } else {
                            if (aF.constructor == Array) {
                                if (aF[0].constructor == Array || aF[0].constructor == String) {
                                    return new ab(aF)
                                } else {
                                    return i.makeAnchor.apply(this, aF)
                                }
                            } else {
                                if (typeof arguments[0] == "object") {
                                    i.extend(ay, aE)
                                }
                            }
                        }
                    }
                } else {
                    ay = {
                        x: aE,
                        y: aB
                    };
                    if (arguments.length >= 4) {
                        ay.orientation = [arguments[2], arguments[3]]
                    }
                    if (arguments.length == 6) {
                        ay.offsets = [arguments[4], arguments[5]]
                    }
                }
                var aD = new ac(ay);
                aD.clone = function () {
                    return new ac(ay)
                };
                return aD
            };
            this.makeAnchors = function (ay) {
                var az = [];
                for (var ax = 0; ax < ay.length; ax++) {
                    if (typeof ay[ax] == "string") {
                        az.push(E.Anchors[ay[ax]]())
                    } else {
                        if (ay[ax].constructor == Array) {
                            az.push(i.makeAnchor(ay[ax]))
                        }
                    }
                }
                return az
            };
            this.makeDynamicAnchor = function (ax, ay) {
                return new ab(ax, ay)
            };
            this.makeTarget = function (az, aA, aG) {
                var ay = i.extend({}, aG);
                i.extend(ay, aA);
                var aF = i.CurrentLibrary,
                    aH = ay.scope || E.Defaults.Scope,
                    aB = ay.deleteEndpointsOnDetach || false,
                    ax = function (aL) {
                        var aJ = i.extend({}, ay.dropOptions || {});
                        var aI = function () {
                                var aM = v(aF.getDragObject(arguments)),
                                    aT = c(aM, "dragId"),
                                    aO = c(aM, "originalScope");
                                if (aO) {
                                    i.CurrentLibrary.setDragScope(aM, aO)
                                }
                                var aQ = Z[aT],
                                    aP = aQ.endpoints[0],
                                    aS = ay.endpoint ? i.extend({}, ay.endpoint) : null,
                                    aN = i.addEndpoint(aL, aS);
                                var aR = i.connect({
                                    source: aP,
                                    target: aN,
                                    scope: aO
                                });
                                if (aB) {
                                    aR.endpointToDeleteOnDetach = aN
                                }
                            };
                        var aK = aF.dragEvents.drop;
                        aJ.scope = aJ.scope || aH;
                        aJ[aK] = am(aJ[aK], aI);
                        aF.initDroppable(aL, aJ)
                    };
                az = x(az);
                var aD = [],
                    aE = az.length && az.constructor != String ? az : [az];
                for (var aC = 0; aC < aE.length; aC++) {
                    ax(v(aE[aC]))
                }
            };
            this.makeTargets = function (az, aA, ax) {
                for (var ay = 0; ay < az.length; ay++) {
                    E.makeTarget(az[ay], aA, ax)
                }
            };
            this.repaint = function (ay) {
                var az = function (aA) {
                        at(v(aA))
                    };
                if (typeof ay == "object") {
                    for (var ax = 0; ax < ay.length; ax++) {
                        az(ay[ax])
                    }
                } else {
                    az(ay)
                }
            };
            this.repaintEverything = function () {
                var ay = ar();
                for (var ax in ah) {
                    at(v(ax), null, ay)
                }
            };
            this.removeAllEndpoints = function (az) {
                var ax = c(az, "id");
                var aA = ah[ax];
                for (var ay in aA) {
                    E.deleteEndpoint(aA[ay])
                }
                ah[ax] = []
            };
            this.removeEveryEndpoint = this.deleteEveryEndpoint;
            this.removeEndpoint = function (ax, ay) {
                E.deleteEndpoint(ay)
            };
            this.reset = function () {
                this.deleteEveryEndpoint();
                this.clearListeners()
            };
            this.setAutomaticRepaint = function (ax) {
                z = ax
            };
            this.setDefaultScope = function (ax) {
                C = ax
            };
            this.setDraggable = w;
            this.setDraggableByDefault = function (ax) {
                Y = ax
            };
            this.setDebugLog = function (ax) {
                G = ax
            };
            this.setRepaintFunction = function (ax) {
                ak = ax
            };
            this.setMouseEventsEnabled = function (ax) {
                ap = ax
            };
            this.CANVAS = "canvas";
            this.SVG = "svg";
            this.VML = "vml";
            this.setRenderMode = function (ax) {
                if (ax) {
                    ax = ax.toLowerCase()
                } else {
                    return
                }
                if (ax !== i.CANVAS && ax !== i.SVG && ax !== i.VML) {
                    throw new Error("render mode must be one of jsPlumb.CANVAS, jsPlumb.SVG or jsPlumb.VML")
                }
                if (ax === i.CANVAS && s) {
                    aa = i.CANVAS
                } else {
                    if (ax === i.SVG && d) {
                        aa = i.SVG
                    } else {
                        if (a) {
                            aa = i.VML
                        }
                    }
                }
                return aa
            };
            this.getRenderMode = function () {
                return aa
            };
            this.show = function (ax) {
                ao(ax, "block")
            };
            this.sizeCanvas = function (az, ax, aB, ay, aA) {
                if (az) {
                    az.style.height = aA + "px";
                    az.height = aA;
                    az.style.width = ay + "px";
                    az.width = ay;
                    az.style.left = ax + "px";
                    az.style.top = aB + "px"
                }
            };
            this.getTestHarness = function () {
                return {
                    endpointsByElement: ah,
                    endpointCount: function (ax) {
                        var ay = ah[ax];
                        return ay ? ay.length : 0
                    },
                    connectionCount: function (ax) {
                        ax = ax || C;
                        var ay = M[ax];
                        return ay ? ay.length : 0
                    },
                    findIndex: l,
                    getId: aw,
                    makeAnchor: self.makeAnchor,
                    makeDynamicAnchor: self.makeDynamicAnchor
                }
            };
            this.toggle = y;
            this.toggleVisible = y;
            this.toggleDraggable = O;
            this.unload = function () {
                delete ah;
                delete U;

                delete W;
                delete Q;
                delete Z;
                delete ag;
                delete af
            };
            this.wrap = am;
            this.addListener = this.bind;
            var ac = function (aB) {
                    var az = this;
                    this.x = aB.x || 0;
                    this.y = aB.y || 0;
                    var ay = aB.orientation || [0, 0];
                    var aA = null,
                        ax = null;
                    this.offsets = aB.offsets || [0, 0];
                    az.timestamp = null;
                    this.compute = function (aH) {
                        var aG = aH.xy,
                            aC = aH.wh,
                            aE = aH.element,
                            aF = aH.timestamp;
                        if (aF && aF === az.timestamp) {
                            return ax
                        }
                        ax = [aG[0] + (az.x * aC[0]) + az.offsets[0], aG[1] + (az.y * aC[1]) + az.offsets[1]];
                        if (aE.canvas) {
                            var aD = aE.canvas.offsetParent.tagName.toLowerCase() === "body" ? {
                                left: 0,
                                top: 0
                            } : n(aE.canvas.offsetParent);
                            ax[0] = ax[0] - aD.left;
                            ax[1] = ax[1] - aD.top
                        }
                        az.timestamp = aF;
                        return ax
                    };
                    this.getOrientation = function () {
                        return ay
                    };
                    this.equals = function (aC) {
                        if (!aC) {
                            return false
                        }
                        var aD = aC.getOrientation();
                        var aE = this.getOrientation();
                        return this.x == aC.x && this.y == aC.y && this.offsets[0] == aC.offsets[0] && this.offsets[1] == aC.offsets[1] && aE[0] == aD[0] && aE[1] == aD[1]
                    };
                    this.getCurrentLocation = function () {
                        return ax
                    }
                };
            var I = function (aD) {
                    var aB = aD.reference;
                    var aC = aD.referenceCanvas;
                    var az = b(v(aC));
                    var ay = 0,
                        aE = 0;
                    var ax = null;
                    var aA = null;
                    this.compute = function (aJ) {
                        var aI = aJ.xy,
                            aH = aJ.element;
                        var aF = [aI[0] + (az[0] / 2), aI[1] + (az[1] / 2)];
                        if (aH.canvas) {
                            var aG = aH.canvas.offsetParent.tagName.toLowerCase() === "body" ? {
                                left: 0,
                                top: 0
                            } : n(aH.canvas.offsetParent);
                            aF[0] = aF[0] - aG.left;
                            aF[1] = aF[1] - aG.top
                        }
                        aA = aF;
                        return aF
                    };
                    this.getOrientation = function () {
                        if (ax) {
                            return ax
                        } else {
                            var aF = aB.getOrientation();
                            return [Math.abs(aF[0]) * ay * -1, Math.abs(aF[1]) * aE * -1]
                        }
                    };
                    this.over = function (aF) {
                        ax = aF.getOrientation()
                    };
                    this.out = function () {
                        ax = null
                    };
                    this.getCurrentLocation = function () {
                        return aA
                    }
                };
            var ab = function (az, ay) {
                    this.isSelective = true;
                    this.isDynamic = true;
                    var aG = [];
                    var aE = function (aH) {
                            return aH.constructor == ac ? aH : i.makeAnchor(aH)
                        };
                    for (var aD = 0; aD < az.length; aD++) {
                        aG[aD] = aE(az[aD])
                    }
                    this.addAnchor = function (aH) {
                        aG.push(aE(aH))
                    };
                    this.getAnchors = function () {
                        return aG
                    };
                    var aA = aG.length > 0 ? aG[0] : null;
                    var aC = aG.length > 0 ? 0 : -1;
                    this.locked = false;
                    var aF = this;
                    var aB = function (aJ, aH, aN, aM, aI) {
                            var aL = aM[0] + (aJ.x * aI[0]),
                                aK = aM[1] + (aJ.y * aI[1]);
                            return Math.sqrt(Math.pow(aH - aL, 2) + Math.pow(aN - aK, 2))
                        };
                    var ax = ay ||
                    function (aR, aI, aJ, aK, aH) {
                        var aM = aJ[0] + (aK[0] / 2),
                            aL = aJ[1] + (aK[1] / 2);
                        var aO = -1,
                            aQ = Infinity;
                        for (var aN = 0; aN < aH.length; aN++) {
                            var aP = aB(aH[aN], aM, aL, aR, aI);
                            if (aP < aQ) {
                                aO = aN + 0;
                                aQ = aP
                            }
                        }
                        return aH[aO]
                    };
                    this.compute = function (aL) {
                        var aK = aL.xy,
                            aH = aL.wh,
                            aJ = aL.timestamp,
                            aI = aL.txy,
                            aN = aL.twh;
                        if (aF.locked || aI == null || aN == null) {
                            return aA.compute(aL)
                        } else {
                            aL.timestamp = null
                        }
                        aA = ax(aK, aH, aI, aN, aG);
                        var aM = aA.compute(aL);
                        return aM
                    };
                    this.getCurrentLocation = function () {
                        var aH = aA != null ? aA.getCurrentLocation() : null;
                        return aH
                    };
                    this.getOrientation = function () {
                        return aA != null ? aA.getOrientation() : [0, 0]
                    };
                    this.over = function (aH) {
                        if (aA != null) {
                            aA.over(aH)
                        }
                    };
                    this.out = function () {
                        if (aA != null) {
                            aA.out()
                        }
                    }
                };
            var H = function (aT) {
                    u.apply(this, arguments);
                    var aI = this;
                    var ay = true;
                    this.isVisible = function () {
                        return ay
                    };
                    this.setVisible = function (aU) {
                        ay = aU;
                        if (aI.connector && aI.connector.canvas) {
                            aI.connector.canvas.style.display = aU ? "block" : "none"
                        }
                    };
                    var aL = new String("_jsplumb_c_" + (new Date()).getTime());
                    this.getId = function () {
                        return aL
                    };
                    this.parent = aT.parent;
                    this.source = v(aT.source);
                    this.target = v(aT.target);
                    if (aT.sourceEndpoint) {
                        this.source = aT.sourceEndpoint.getElement()
                    }
                    if (aT.targetEndpoint) {
                        this.target = aT.targetEndpoint.getElement()
                    }
                    this.sourceId = c(this.source, "id");
                    this.targetId = c(this.target, "id");
                    this.endpointsOnTop = aT.endpointsOnTop != null ? aT.endpointsOnTop : true;
                    this.getAttachedElements = function () {
                        return aI.endpoints
                    };
                    var aF = null,
                        aE = null;
                    this.savePosition = function () {
                        aF = i.CurrentLibrary.getOffset(i.CurrentLibrary.getElementObject(aI.source));
                        aE = i.CurrentLibrary.getOffset(i.CurrentLibrary.getElementObject(aI.target))
                    };
                    this.scope = aT.scope;
                    this.endpoints = [];
                    this.endpointStyles = [];
                    var aS = function (aU) {
                            if (aU) {
                                return i.makeAnchor(aU)
                            }
                        };
                    var aO = function (aU, aZ, aV, aX, aW, aY) {
                            if (aU) {
                                aI.endpoints[aZ] = aU;
                                aU.addConnection(aI)
                            } else {
                                if (!aV.endpoints) {
                                    aV.endpoints = [null, null]
                                }
                                var a5 = aV.endpoints[aZ] || aV.endpoint || E.Defaults.Endpoints[aZ] || i.Defaults.Endpoints[aZ] || E.Defaults.Endpoint || i.Defaults.Endpoint;
                                if (!aV.endpointStyles) {
                                    aV.endpointStyles = [null, null]
                                }
                                if (!aV.endpointHoverStyles) {
                                    aV.endpointHoverStyles = [null, null]
                                }
                                var a3 = aV.endpointStyles[aZ] || aV.endpointStyle || E.Defaults.EndpointStyles[aZ] || i.Defaults.EndpointStyles[aZ] || E.Defaults.EndpointStyle || i.Defaults.EndpointStyle;
                                if (a3.fillStyle == null && aW != null) {
                                    a3.fillStyle = aW.strokeStyle
                                }
                                if (a3.outlineColor == null && aW != null) {
                                    a3.outlineColor = aW.outlineColor
                                }
                                if (a3.outlineWidth == null && aW != null) {
                                    a3.outlineWidth = aW.outlineWidth
                                }
                                var a2 = aV.endpointHoverStyles[aZ] || aV.endpointHoverStyle || E.Defaults.EndpointHoverStyles[aZ] || i.Defaults.EndpointHoverStyles[aZ] || E.Defaults.EndpointHoverStyle || i.Defaults.EndpointHoverStyle;
                                if (aY != null) {
                                    if (a2 == null) {
                                        a2 = {}
                                    }
                                    if (a2.fillStyle == null) {
                                        a2.fillStyle = aY.strokeStyle
                                    }
                                }
                                var a1 = aV.anchors ? aV.anchors[aZ] : aS(E.Defaults.Anchors[aZ]) || aS(i.Defaults.Anchors[aZ]) || aS(E.Defaults.Anchor) || aS(i.Defaults.Anchor);
                                var a4 = aV.uuids ? aV.uuids[aZ] : null;
                                var a0 = an({
                                    paintStyle: a3,
                                    hoverPaintStyle: a2,
                                    endpoint: a5,
                                    connections: [aI],
                                    uuid: a4,
                                    anchor: a1,
                                    source: aX
                                });
                                aI.endpoints[aZ] = a0;
                                if (aV.drawEndpoints === false) {
                                    a0.setVisible(false, true, true)
                                }
                                return a0
                            }
                        };
                    var aK = aO(aT.sourceEndpoint, 0, aT, aI.source, aT.paintStyle, aT.hoverPaintStyle);
                    if (aK) {
                        T(ah, this.sourceId, aK)
                    }
                    var aJ = aO(aT.targetEndpoint, 1, aT, aI.target, aT.paintStyle, aT.hoverPaintStyle);
                    if (aJ) {
                        T(ah, this.targetId, aJ)
                    }
                    if (!this.scope) {
                        this.scope = this.endpoints[0].scope
                    }
                    this.setConnector = function (aV, aY) {
                        if (aI.connector != null) {
                            R(aI.connector.getDisplayElements(), aI.parent)
                        }
                        var aZ = {
                            _jsPlumb: aI._jsPlumb,
                            parent: aT.parent,
                            cssClass: aT.cssClass
                        };
                        if (aV.constructor == String) {
                            this.connector = new i.Connectors[aa][aV](aZ)
                        } else {
                            if (aV.constructor == Array) {
                                this.connector = new i.Connectors[aa][aV[0]](i.extend(aV[1], aZ))
                            }
                        }
                        this.canvas = this.connector.canvas;
                        var aU = false,
                            aX = false,
                            aW = null;
                        this.connector.bind("click", function (a0, a1) {
                            aX = false;
                            aI.fire("click", aI, a1)
                        });
                        this.connector.bind("dblclick", function (a0, a1) {
                            aX = false;
                            aI.fire("dblclick", aI, a1)
                        });
                        this.connector.bind("mouseenter", function (a0, a1) {
                            if (!aI.isHover()) {
                                if (q == null) {
                                    aI.setHover(true)
                                }
                                aI.fire("mouseenter", aI, a1)
                            }
                        });
                        this.connector.bind("mouseexit", function (a0, a1) {
                            if (aI.isHover()) {
                                if (q == null) {
                                    aI.setHover(false)
                                }
                                aI.fire("mouseexit", aI, a1)
                            }
                        });
                        this.connector.bind("mousedown", function (a0, a1) {
                            aU = true;
                            aW = i.CurrentLibrary.getPageXY(a1);
                            aI.savePosition()
                        });
                        this.connector.bind("mouseup", function (a0, a1) {
                            aU = false;
                            if (aI.connector == q) {
                                q = null
                            }
                        });
                        if (!aY) {
                            aI.repaint()
                        }
                    };
                    aI.setConnector(this.endpoints[0].connector || this.endpoints[1].connector || aT.connector || E.Defaults.Connector || i.Defaults.Connector, true);
                    this.setPaintStyle(this.endpoints[0].connectorStyle || this.endpoints[1].connectorStyle || aT.paintStyle || E.Defaults.PaintStyle || i.Defaults.PaintStyle, true);
                    this.setHoverPaintStyle(this.endpoints[0].connectorHoverStyle || this.endpoints[1].connectorHoverStyle || aT.hoverPaintStyle || E.Defaults.HoverPaintStyle || i.Defaults.HoverPaintStyle, true);
                    this.paintStyleInUse = this.paintStyle;
                    this.overlays = [];
                    var az = aT.overlays || E.Defaults.Overlays;
                    if (az) {
                        for (var aQ = 0; aQ < az.length; aQ++) {
                            var aP = az[aQ],
                                aC = null,
                                aR = null;
                            if (aP.constructor == Array) {
                                var aA = aP[0];
                                var aM = i.CurrentLibrary.extend({
                                    connection: aI,
                                    _jsPlumb: E
                                }, aP[1]);
                                if (aP.length == 3) {
                                    i.CurrentLibrary.extend(aM, aP[2])
                                }
                                aC = new i.Overlays[aa][aA](aM);
                                if (aM.events) {
                                    for (var aH in aM.events) {
                                        aC.bind(aH, aM.events[aH])
                                    }
                                }
                            } else {
                                if (aP.constructor == String) {
                                    aC = new i.Overlays[aa][aP]({
                                        connection: aI,
                                        _jsPlumb: E
                                    })
                                } else {
                                    aC = aP
                                }
                            }
                            this.overlays.push(aC)
                        }
                    }
                    this.addOverlay = function (aU) {
                        aI.overlays.push(aU)
                    };
                    this.removeAllOverlays = function () {
                        aI.overlays.splice(0, aI.overlays.length);
                        aI.repaint()
                    };
                    this.removeOverlay = function (aW) {
                        var aU = -1;
                        for (var aV = 0; aV < aI.overlays.length; aV++) {
                            if (aW === aI.overlays[aV].id) {
                                aU = aV;
                                break
                            }
                        }
                        if (aU != -1) {
                            aI.overlays.splice(aU, 1)
                        }
                    };
                    this.removeOverlays = function () {
                        for (var aU = 0; aU < arguments.length; aU++) {
                            aI.removeOverlay(arguments[aU])
                        }
                    };
                    this.labelStyle = aT.labelStyle || E.Defaults.LabelStyle || i.Defaults.LabelStyle;
                    this.label = aT.label;
                    if (this.label) {
                        this.overlays.push(new i.Overlays[aa].Label({
                            cssClass: aT.cssClass,
                            labelStyle: this.labelStyle,
                            label: this.label,
                            connection: aI,
                            _jsPlumb: E
                        }))
                    }
                    F({
                        elId: this.sourceId
                    });
                    F({
                        elId: this.targetId
                    });
                    this.setLabel = function (aU) {
                        aI.label = aU;
                        E.repaint(aI.source)
                    };
                    var aD = W[this.sourceId],
                        aB = Q[this.sourceId];
                    var ax = W[this.targetId];
                    var aG = Q[this.targetId];
                    var aN = this.endpoints[0].anchor.compute({
                        xy: [aD.left, aD.top],
                        wh: aB,
                        element: this.endpoints[0],
                        txy: [ax.left, ax.top],
                        twh: aG,
                        tElement: this.endpoints[1]
                    });
                    this.endpoints[0].paint({
                        anchorLoc: aN
                    });
                    aN = this.endpoints[1].anchor.compute({
                        xy: [ax.left, ax.top],
                        wh: aG,
                        element: this.endpoints[1],
                        txy: [aD.left, aD.top],
                        twh: aB,
                        tElement: this.endpoints[0]
                    });
                    this.endpoints[1].paint({
                        anchorLoc: aN
                    });
                    this.paint = function (bd) {
                        bd = bd || {};
                        var a3 = bd.elId,
                            a5 = bd.ui,
                            a2 = bd.recalc,
                            aV = bd.timestamp;
                        var aY = aI.floatingAnchorIndex;
                        var a6 = false;
                        var bc = a6 ? this.sourceId : this.targetId,
                            a1 = a6 ? this.targetId : this.sourceId;
                        var aW = a6 ? 0 : 1,
                            be = a6 ? 1 : 0;
                        var aU = a6 ? this.target : this.source;
                        F({
                            elId: a3,
                            offset: a5,
                            recalc: a2,
                            timestamp: aV
                        });
                        F({
                            elId: bc,
                            timestamp: aV
                        });
                        var aZ = this.endpoints[be].anchor.getCurrentLocation();
                        var a0 = this.endpoints[be].anchor.getOrientation();
                        var ba = this.endpoints[aW].anchor.getCurrentLocation();
                        var bb = this.endpoints[aW].anchor.getOrientation();
                        var aX = 0;
                        for (var a9 = 0; a9 < aI.overlays.length; a9++) {
                            var a7 = aI.overlays[a9];
                            var a4 = a7.computeMaxSize(aI.connector);
                            if (a4 > aX) {
                                aX = a4
                            }
                        }
                        var a8 = this.connector.compute(aZ, ba, this.endpoints[be].anchor, this.endpoints[aW].anchor, aI.paintStyleInUse.lineWidth, aX);
                        aI.connector.paint(a8, aI.paintStyleInUse);
                        for (var a9 = 0; a9 < aI.overlays.length; a9++) {
                            var a7 = aI.overlays[a9];
                            aI.overlayPlacements[a9] = a7.draw(aI.connector, aI.paintStyleInUse, a8)
                        }
                    };
                    this.repaint = function () {
                        this.paint({
                            elId: this.sourceId,
                            recalc: true
                        })
                    };
                    P(aI.source, aT.draggable, aT.dragOptions);
                    P(aI.target, aT.draggable, aT.dragOptions);
                    if (this.source.resize) {
                        this.source.resize(function (aU) {
                            i.repaint(aI.sourceId)
                        })
                    }
                    aI.repaint()
                };
            var aq = function (aX) {
                    i.jsPlumbUIComponent.apply(this, arguments);
                    aX = aX || {};
                    var aM = this;
                    var az = true;
                    this.isVisible = function () {
                        return az
                    };
                    this.setVisible = function (aZ, a2, aY) {
                        az = aZ;
                        if (aM.canvas) {
                            aM.canvas.style.display = aZ ? "block" : "none"
                        }
                        if (!a2) {
                            for (var a1 = 0; a1 < aM.connections.length; a1++) {
                                aM.connections[a1].setVisible(aZ);
                                if (!aY) {
                                    var a0 = aM === aM.connections[a1].endpoints[0] ? 1 : 0;
                                    if (aM.connections[a1].endpoints[a0].connections.length == 1) {
                                        aM.connections[a1].endpoints[a0].setVisible(aZ, true, true)
                                    }
                                }
                            }
                        }
                    };
                    var aN = new String("_jsplumb_e_" + (new Date()).getTime());
                    this.getId = function () {
                        return aN
                    };
                    if (aX.dynamicAnchors) {
                        aM.anchor = new ab(i.makeAnchors(aX.dynamicAnchors))
                    } else {
                        aM.anchor = aX.anchor ? i.makeAnchor(aX.anchor) : aX.anchors ? i.makeAnchor(aX.anchors) : i.makeAnchor("TopCenter")
                    }
                    var aK = aX.endpoint || E.Defaults.Endpoint || i.Defaults.Endpoint || "Dot",
                        aF = {
                            _jsPlumb: aM._jsPlumb,
                            parent: aX.parent
                        };
                    if (aK.constructor == String) {
                        aK = new i.Endpoints[aa][aK](aF)
                    } else {
                        if (aK.constructor == Array) {
                            aK = new i.Endpoints[aa][aK[0]](i.extend(aK[1], aF))
                        } else {
                            aK = aK.clone()
                        }
                    }
                    aM.endpoint = aK;
                    this.endpoint.bind("click", function (aY) {
                        aM.fire("click", aM, aY)
                    });
                    this.endpoint.bind("dblclick", function (aY) {
                        aM.fire("dblclick", aM, aY)
                    });
                    this.setPaintStyle(aX.paintStyle || aX.style || E.Defaults.EndpointStyle || i.Defaults.EndpointStyle, true);
                    this.setHoverPaintStyle(aX.hoverPaintStyle || E.Defaults.EndpointHoverStyle || i.Defaults.EndpointHoverStyle, true);
                    this.paintStyleInUse = this.paintStyle;
                    this.connectorStyle = aX.connectorStyle;
                    this.connectorHoverStyle = aX.connectorHoverStyle;
                    this.connectorOverlays = aX.connectorOverlays;
                    this.connector = aX.connector;
                    this.parent = aX.parent;
                    this.isSource = aX.isSource || false;
                    this.isTarget = aX.isTarget || false;
                    var aL = aX.source,
                        aH = aX.uuid,
                        aV = null,
                        aB = null;
                    if (aH) {
                        U[aH] = aM
                    }
                    var aE = c(aL, "id");
                    this.elementId = aE;
                    this.element = aL;
                    var aS = aX.maxConnections || E.Defaults.MaxConnections;
                    this.getAttachedElements = function () {
                        return aM.connections
                    };
                    this.canvas = this.endpoint.canvas;
                    this.connections = aX.connections || [];
                    this.scope = aX.scope || C;
                    this.timestamp = null;
                    var aJ = aX.reattach || false;
                    var aI = aX.dragAllowedWhenFull || true;
                    this.computeAnchor = function (aY) {
                        return aM.anchor.compute(aY)
                    };
                    this.addConnection = function (aY) {
                        aM.connections.push(aY)
                    };
                    this.detach = function (aZ, a1) {
                        var aY = l(aM.connections, aZ);
                        if (aY >= 0) {
                            aM.connections.splice(aY, 1);
                            if (!a1) {
                                var a0 = aZ.endpoints[0] == aM ? aZ.endpoints[1] : aZ.endpoints[0];
                                a0.detach(aZ, true);
                                if (aZ.endpointToDeleteOnDetach && aZ.endpointToDeleteOnDetach.connections.length == 0) {
                                    i.deleteEndpoint(aZ.endpointToDeleteOnDetach)
                                }
                            }
                            R(aZ.connector.getDisplayElements(), aZ.parent);
                            N(M, aZ.scope, aZ);
                            if (!a1) {
                                ad(aZ)
                            }
                        }
                    };
                    this.detachAll = function () {
                        while (aM.connections.length > 0) {
                            aM.detach(aM.connections[0])
                        }
                    };
                    this.detachFrom = function (aZ) {
                        var a0 = [];
                        for (var aY = 0; aY < aM.connections.length; aY++) {
                            if (aM.connections[aY].endpoints[1] == aZ || aM.connections[aY].endpoints[0] == aZ) {
                                a0.push(aM.connections[aY])
                            }
                        }
                        for (var aY = 0; aY < a0.length; aY++) {
                            a0[aY].setHover(false);
                            aM.detach(a0[aY])
                        }
                    };
                    this.detachFromConnection = function (aZ) {
                        var aY = l(aM.connections, aZ);
                        if (aY >= 0) {
                            aM.connections.splice(aY, 1)
                        }
                    };
                    this.getElement = function () {
                        return aL
                    };
                    this.getUuid = function () {
                        return aH
                    };
                    this.makeInPlaceCopy = function () {
                        return an({
                            anchor: aM.anchor,
                            source: aL,
                            paintStyle: this.paintStyle,
                            endpoint: aK
                        })
                    };
                    this.isConnectedTo = function (a0) {
                        var aZ = false;
                        if (a0) {
                            for (var aY = 0; aY < aM.connections.length; aY++) {
                                if (aM.connections[aY].endpoints[1] == a0) {
                                    aZ = true;
                                    break
                                }
                            }
                        }
                        return aZ
                    };
                    this.isFloating = function () {
                        return aV != null
                    };
                    this.connectorSelector = function () {
                        return (aM.connections.length < aS) ? null : aM.connections[0]
                    };
                    this.isFull = function () {
                        return !(aM.isFloating() || aS < 1 || aM.connections.length < aS)
                    };
                    this.setDragAllowedWhenFull = function (aY) {
                        aI = aY
                    };
                    this.setStyle = aM.setPaintStyle;
                    this.equals = function (aY) {
                        return this.anchor.equals(aY.anchor)
                    };
                    this.paint = function (a1) {
                        a1 = a1 || {};
                        var a5 = a1.timestamp;
                        if (!a5 || aM.timestamp !== a5) {
                            var a4 = a1.anchorPoint,
                                a0 = a1.canvas,
                                a2 = a1.connectorPaintStyle;
                            if (a4 == null) {
                                var bb = a1.offset || W[aE];
                                var aY = a1.dimensions || Q[aE];
                                if (bb == null || aY == null) {
                                    F({
                                        elId: aE,
                                        timestamp: a5
                                    });
                                    bb = W[aE];
                                    aY = Q[aE]
                                }
                                var aZ = {
                                    xy: [bb.left, bb.top],
                                    wh: aY,
                                    element: aM,
                                    timestamp: a5
                                };
                                if (aM.anchor.isDynamic) {
                                    if (aM.connections.length > 0) {
                                        var a8 = aM.connections[0];
                                        var ba = a8.endpoints[0] == aM ? 1 : 0;
                                        var a3 = ba == 0 ? a8.sourceId : a8.targetId;
                                        var a7 = W[a3],
                                            a9 = Q[a3];
                                        aZ.txy = [a7.left, a7.top];
                                        aZ.twh = a9;
                                        aZ.tElement = a8.endpoints[ba]
                                    }
                                }
                                a4 = aM.anchor.compute(aZ)
                            }
                            var a6 = aK.compute(a4, aM.anchor.getOrientation(), aM.paintStyleInUse, a2 || aM.paintStyleInUse);
                            aK.paint(a6, aM.paintStyleInUse, aM.anchor);
                            aM.timestamp = a5
                        }
                    };
                    this.repaint = this.paint;
                    this.removeConnection = this.detach;
                    if (aX.isSource && i.CurrentLibrary.isDragSupported(aL)) {
                        var aR = null,
                            aN = null,
                            aQ = null,
                            ax = false,
                            aA = null;
                        var aC = function () {
                                aQ = aM.connectorSelector();
                                if (aM.isFull() && !aI) {
                                    return false
                                }
                                F({
                                    elId: aE
                                });
                                aB = aM.makeInPlaceCopy();
                                aB.paint();
                                aR = document.createElement("div");
                                aR.style.position = "absolute";
                                var a5 = v(aR);
                                S(aR, aM.parent);
                                var aY = aw(a5);
                                var a6 = v(aB.canvas),
                                    a4 = i.CurrentLibrary.getOffset(a6),
                                    a0 = aB.canvas.offsetParent.tagName.toLowerCase() === "body" ? {
                                        left: 0,
                                        top: 0
                                    } : n(aB.canvas.offsetParent);
                                i.CurrentLibrary.setOffset(aR, {
                                    left: a4.left - a0.left,
                                    top: a4.top - a0.top
                                });
                                F({
                                    elId: aY
                                });
                                e(v(aM.canvas), "dragId", aY);
                                e(v(aM.canvas), "elId", aE);
                                var a7 = new I({
                                    reference: aM.anchor,
                                    referenceCanvas: aM.canvas
                                });
                                aV = an({
                                    paintStyle: aM.paintStyle,
                                    endpoint: aK,
                                    anchor: a7,
                                    source: a5
                                });
                                if (aQ == null) {
                                    aM.anchor.locked = true;
                                    aQ = au({
                                        sourceEndpoint: aM,
                                        targetEndpoint: aV,
                                        source: v(aL),
                                        target: v(aR),
                                        anchors: [aM.anchor, a7],
                                        paintStyle: aX.connectorStyle,
                                        hoverPaintStyle: aX.connectorHoverStyle,
                                        connector: aX.connector,
                                        overlays: aX.connectorOverlays
                                    });
                                    aQ.connector.setHover(false)
                                } else {
                                    ax = true;
                                    aQ.connector.setHover(false);
                                    aD(v(aB.canvas));
                                    var aZ = aQ.sourceId == aE ? 0 : 1;
                                    aQ.floatingAnchorIndex = aZ;
                                    aM.detachFromConnection(aQ);
                                    var a3 = v(aM.canvas);
                                    var a2 = i.CurrentLibrary.getDragScope(a3);
                                    e(a3, "originalScope", a2);
                                    var a1 = "scope_" + (new Date()).getTime();
                                    if (aZ == 0) {
                                        aA = [aQ.source, aQ.sourceId, aU, a2];
                                        aQ.source = v(aR);
                                        aQ.sourceId = aY
                                    } else {
                                        aA = [aQ.target, aQ.targetId, aU, a2];
                                        aQ.target = v(aR);
                                        aQ.targetId = aY
                                    }
                                    i.CurrentLibrary.setDragScope(aU, a1);
                                    aQ.endpoints[aZ == 0 ? 1 : 0].anchor.locked = true;
                                    aQ.suspendedEndpoint = aQ.endpoints[aZ];
                                    aQ.endpoints[aZ] = aV
                                }
                                Z[aY] = aQ;
                                aV.addConnection(aQ);
                                T(ah, aY, aV);
                                E.currentlyDragging = true
                            };
                        var ay = i.CurrentLibrary,
                            aT = aX.dragOptions || {},
                            aO = i.extend({}, ay.defaultDragOptions),
                            aP = ay.dragEvents.start,
                            aW = ay.dragEvents.stop,
                            aG = ay.dragEvents.drag;
                        aT = i.extend(aO, aT);
                        aT.scope = aT.scope || aM.scope;
                        aT[aP] = am(aT[aP], aC);
                        aT[aG] = am(aT[aG], function () {
                            var aY = i.CurrentLibrary.getUIPosition(arguments);
                            i.CurrentLibrary.setOffset(aR, aY);
                            at(v(aR), aY)
                        });
                        aT[aW] = am(aT[aW], function () {
                            N(ah, aN, aV);
                            R([aR, aV.canvas], aL);
                            ae(aB.canvas, aL);
                            var aY = aQ.floatingAnchorIndex == null ? 1 : aQ.floatingAnchorIndex;
                            aQ.endpoints[aY == 0 ? 1 : 0].anchor.locked = false;
                            if (aQ.endpoints[aY] == aV) {
                                if (ax && aQ.suspendedEndpoint) {
                                    if (aY == 0) {
                                        aQ.source = aA[0];
                                        aQ.sourceId = aA[1]
                                    } else {
                                        aQ.target = aA[0];
                                        aQ.targetId = aA[1]
                                    }
                                    i.CurrentLibrary.setDragScope(aA[2], aA[3]);
                                    aQ.endpoints[aY] = aQ.suspendedEndpoint;
                                    if (aJ) {
                                        aQ.floatingAnchorIndex = null;
                                        aQ.suspendedEndpoint.addConnection(aQ);
                                        i.repaint(aA[1])
                                    } else {
                                        aQ.endpoints[aY == 0 ? 1 : 0].detach(aQ)
                                    }
                                } else {
                                    R(aQ.connector.getDisplayElements(), aM.parent);
                                    aM.detachFromConnection(aQ)
                                }
                            }
                            aM.anchor.locked = false;
                            aM.paint();
                            aQ.setHover(false);
                            aQ.repaint();
                            aQ = null;
                            delete aB;
                            delete ah[aV.elementId];
                            aV = null;
                            delete aV;
                            E.currentlyDragging = false
                        });
                        var aU = v(aM.canvas);
                        i.CurrentLibrary.initDraggable(aU, aT)
                    }
                    var aD = function (a1) {
                            if (aX.isTarget && i.CurrentLibrary.isDropSupported(aL)) {
                                var aY = aX.dropOptions || E.Defaults.DropOptions || i.Defaults.DropOptions;
                                aY = i.extend({}, aY);
                                aY.scope = aY.scope || aM.scope;
                                var a4 = null;
                                var a2 = i.CurrentLibrary.dragEvents.drop;
                                var a3 = i.CurrentLibrary.dragEvents.over;
                                var aZ = i.CurrentLibrary.dragEvents.out;
                                var a0 = function () {
                                        var bd = v(i.CurrentLibrary.getDragObject(arguments));
                                        var a5 = c(bd, "dragId");
                                        var a7 = c(bd, "elId");
                                        var bc = c(bd, "originalScope");
                                        if (bc) {
                                            i.CurrentLibrary.setDragScope(bd, bc)
                                        }
                                        var a9 = Z[a5];
                                        var ba = a9.floatingAnchorIndex == null ? 1 : a9.floatingAnchorIndex,
                                            bb = ba == 0 ? 1 : 0;
                                        if (!aM.isFull() && !(ba == 0 && !aM.isSource) && !(ba == 1 && !aM.isTarget)) {
                                            if (ba == 0) {
                                                a9.source = aL;
                                                a9.sourceId = aE
                                            } else {
                                                a9.target = aL;
                                                a9.targetId = aE
                                            }
                                            a9.endpoints[ba].detachFromConnection(a9);
                                            if (a9.suspendedEndpoint) {
                                                a9.suspendedEndpoint.detachFromConnection(a9)
                                            }
                                            a9.endpoints[ba] = aM;
                                            aM.addConnection(a9);
                                            if (!a9.suspendedEndpoint) {
                                                T(M, a9.scope, a9);
                                                P(aL, aX.draggable, {})
                                            } else {
                                                var a8 = a9.suspendedEndpoint.getElement(),
                                                    a6 = a9.suspendedEndpoint.elementId;
                                                E.fire("jsPlumbConnectionDetached", {
                                                    source: ba == 0 ? a8 : a9.source,
                                                    target: ba == 1 ? a8 : a9.target,
                                                    sourceId: ba == 0 ? a6 : a9.sourceId,
                                                    targetId: ba == 1 ? a6 : a9.targetId,
                                                    sourceEndpoint: ba == 0 ? a9.suspendedEndpoint : a9.endpoints[0],
                                                    targetEndpoint: ba == 1 ? a9.suspendedEndpoint : a9.endpoints[1],
                                                    connection: a9
                                                })
                                            }
                                            i.repaint(a7);
                                            E.fire("jsPlumbConnection", {
                                                source: a9.source,
                                                target: a9.target,
                                                sourceId: a9.sourceId,
                                                targetId: a9.targetId,
                                                sourceEndpoint: a9.endpoints[0],
                                                targetEndpoint: a9.endpoints[1],
                                                connection: a9
                                            })
                                        }
                                        E.currentlyDragging = false;
                                        delete Z[a5]
                                    };
                                aY[a2] = am(aY[a2], a0);
                                aY[a3] = am(aY[a3], function () {
                                    var a6 = i.CurrentLibrary.getDragObject(arguments);
                                    var a8 = c(v(a6), "dragId");
                                    var a7 = Z[a8];
                                    if (a7 != null) {
                                        var a5 = a7.floatingAnchorIndex == null ? 1 : a7.floatingAnchorIndex;
                                        a7.endpoints[a5].anchor.over(aM.anchor)
                                    }
                                });
                                aY[aZ] = am(aY[aZ], function () {
                                    var a6 = i.CurrentLibrary.getDragObject(arguments),
                                        a8 = c(v(a6), "dragId"),
                                        a7 = Z[a8];
                                    if (a7 != null) {
                                        var a5 = a7.floatingAnchorIndex == null ? 1 : a7.floatingAnchorIndex;
                                        a7.endpoints[a5].anchor.out()
                                    }
                                });
                                i.CurrentLibrary.initDroppable(a1, aY)
                            }
                        };
                    aD(v(aM.canvas));
                    return aM
                }
        };
    var i = window.jsPlumb = new r();
    i.getInstance = function (x) {
        var w = new r(x);
        return w
    };
    var m = function (w, B, A, z) {
            return function () {
                return i.makeAnchor(w, B, A, z)
            }
        };
    i.Anchors.TopCenter = m(0.5, 0, 0, -1);
    i.Anchors.BottomCenter = m(0.5, 1, 0, 1);
    i.Anchors.LeftMiddle = m(0, 0.5, -1, 0);
    i.Anchors.RightMiddle = m(1, 0.5, 1, 0);
    i.Anchors.Center = m(0.5, 0.5, 0, 0);
    i.Anchors.TopRight = m(1, 0, 0, -1);
    i.Anchors.BottomRight = m(1, 1, 0, 1);
    i.Anchors.TopLeft = m(0, 0, 0, -1);
    i.Anchors.BottomLeft = m(0, 1, 0, 1);
    i.Defaults.DynamicAnchors = function () {
        return i.makeAnchors(["TopCenter", "RightMiddle", "BottomCenter", "LeftMiddle"])
    };
    i.Anchors.AutoDefault = function () {
        return i.makeDynamicAnchor(i.Defaults.DynamicAnchors())
    }
})();
(function () {
    jsPlumb.DOMElementComponent = function (a) {
        jsPlumb.jsPlumbUIComponent.apply(this, arguments);
        this.mousemove = this.dblclick = this.click = this.mousedown = this.mouseup = function (b) {}
    };
    jsPlumb.Connectors.Straight = function () {
        var n = this;
        var g = null;
        var c, h, l, k, i, d, m, f, e, b, a;
        this.compute = function (r, F, B, o, v, q) {
            var E = Math.abs(r[0] - F[0]);
            var u = Math.abs(r[1] - F[1]);
            var z = false,
                s = false;
            var t = 0.45 * E,
                p = 0.45 * u;
            E *= 1.9;
            u *= 1.9;
            var C = Math.min(r[0], F[0]) - t;
            var A = Math.min(r[1], F[1]) - p;
            var D = Math.max(2 * v, q);
            if (E < D) {
                E = D;
                C = r[0] + ((F[0] - r[0]) / 2) - (D / 2);
                t = (E - Math.abs(r[0] - F[0])) / 2
            }
            if (u < D) {
                u = D;
                A = r[1] + ((F[1] - r[1]) / 2) - (D / 2);
                p = (u - Math.abs(r[1] - F[1])) / 2
            }
            f = r[0] < F[0] ? t : E - t;
            e = r[1] < F[1] ? p : u - p;
            b = r[0] < F[0] ? E - t : t;
            a = r[1] < F[1] ? u - p : p;
            g = [C, A, E, u, f, e, b, a];
            k = b - f, i = (a - e);
            c = i / k, h = -1 / c;
            l = -1 * ((c * f) - e);
            d = Math.atan(c);
            m = Math.atan(h);
            return g
        };
        this.pointOnPath = function (o) {
            var p = f + (o * k);
            var q = (c == Infinity || c == -Infinity) ? e + (o * (a - e)) : (c * p) + l;
            return {
                x: p,
                y: q
            }
        };
        this.gradientAtPoint = function (o) {
            return c
        };
        this.pointAlongPathFrom = function (q, u) {
            var s = n.pointOnPath(q);
            var r = u > 0 ? 1 : -1;
            var t = Math.abs(u * Math.sin(d));
            if (e > a) {
                t = t * -1
            }
            var o = Math.abs(u * Math.cos(d));
            if (f > b) {
                o = o * -1
            }
            return {
                x: s.x + (r * o),
                y: s.y + (r * t)
            }
        };
        this.perpendicularToPathAt = function (s, t, w) {
            var u = n.pointAlongPathFrom(s, w);
            var r = n.gradientAtPoint(u.location);
            var q = Math.atan(-1 / r);
            var v = t / 2 * Math.sin(q);
            var o = t / 2 * Math.cos(q);
            return [{
                x: u.x + o,
                y: u.y + v
            }, {
                x: u.x - o,
                y: u.y - v
            }]
        }
    };
    jsPlumb.Connectors.Bezier = function (e) {
        var o = this;
        e = e || {};
        this.majorAnchor = e.curviness || 150;
        this.minorAnchor = 10;
        var h = null;
        this._findControlPoint = function (z, q, u, x, r) {
            var w = x.getOrientation(),
                y = r.getOrientation();
            var t = w[0] != y[0] || w[1] == y[1];
            var s = [];
            var A = o.majorAnchor,
                v = o.minorAnchor;
            if (!t) {
                if (w[0] == 0) {
                    s.push(q[0] < u[0] ? z[0] + v : z[0] - v)
                } else {
                    s.push(z[0] - (A * w[0]))
                }
                if (w[1] == 0) {
                    s.push(q[1] < u[1] ? z[1] + v : z[1] - v)
                } else {
                    s.push(z[1] + (A * y[1]))
                }
            } else {
                if (y[0] == 0) {
                    s.push(u[0] < q[0] ? z[0] + v : z[0] - v)
                } else {
                    s.push(z[0] + (A * y[0]))
                }
                if (y[1] == 0) {
                    s.push(u[1] < q[1] ? z[1] + v : z[1] - v)
                } else {
                    s.push(z[1] + (A * w[1]))
                }
            }
            return s
        };
        var n, m, i, b, a, i, f, d, c, l, g;
        this.compute = function (J, s, H, q, p, D) {
            p = p || 0;
            l = Math.abs(J[0] - s[0]) + p;
            g = Math.abs(J[1] - s[1]) + p;
            d = Math.min(J[0], s[0]) - (p / 2);
            c = Math.min(J[1], s[1]) - (p / 2);
            i = J[0] < s[0] ? l - (p / 2) : (p / 2);
            f = J[1] < s[1] ? g - (p / 2) : (p / 2);
            b = J[0] < s[0] ? (p / 2) : l - (p / 2);
            a = J[1] < s[1] ? (p / 2) : g - (p / 2);
            n = o._findControlPoint([i, f], J, s, H, q);
            m = o._findControlPoint([b, a], s, J, q, H);
            var C = Math.min(i, b);
            var B = Math.min(n[0], m[0]);
            var x = Math.min(C, B);
            var I = Math.max(i, b);
            var F = Math.max(n[0], m[0]);
            var u = Math.max(I, F);
            if (u > l) {
                l = u
            }
            if (x < 0) {
                d += x;
                var y = Math.abs(x);
                l += y;
                n[0] += y;
                i += y;
                b += y;
                m[0] += y
            }
            var G = Math.min(f, a);
            var E = Math.min(n[1], m[1]);
            var t = Math.min(G, E);
            var z = Math.max(f, a);
            var w = Math.max(n[1], m[1]);
            var r = Math.max(z, w);
            if (r > g) {
                g = r
            }
            if (t < 0) {
                c += t;
                var v = Math.abs(t);
                g += v;
                n[1] += v;
                f += v;
                a += v;
                m[1] += v
            }
            if (D && l < D) {
                var A = (D - l) / 2;
                l = D;
                d -= A;
                i = i + A;
                b = b + A;
                n[0] = n[0] + A;
                m[0] = m[0] + A
            }
            if (D && g < D) {
                var A = (D - g) / 2;
                g = D;
                c -= A;
                f = f + A;
                a = a + A;
                n[1] = n[1] + A;
                m[1] = m[1] + A
            }
            h = [d, c, l, g, i, f, b, a, n[0], n[1], m[0], m[1]];
            return h
        };
        var k = function () {
                return [{
                    x: i,
                    y: f
                }, {
                    x: n[0],
                    y: n[1]
                }, {
                    x: m[0],
                    y: m[1]
                }, {
                    x: b,
                    y: a
                }]
            };
        this.pointOnPath = function (p) {
            return jsBezier.pointOnCurve(k(), p)
        };
        this.gradientAtPoint = function (p) {
            return jsBezier.gradientAtPoint(k(), p)
        };
        this.pointAlongPathFrom = function (p, q) {
            return jsBezier.pointAlongCurveFrom(k(), p, q)
        };
        this.perpendicularToPathAt = function (p, q, r) {
            return jsBezier.perpendicularToCurveAt(k(), p, q, r)
        }
    };
    jsPlumb.Connectors.Flowchart = function (f) {
        f = f || {};
        var n = this,
            b = f.stub || f.minStubLength || 30,
            i = [],
            h = [],
            l = [],
            g = [],
            a = [],
            m = [],
            d, c, p = function (t, s, A, z) {
                var x = 0;
                for (var r = 0; r < i.length; r++) {
                    var y = r == 0 ? t : i[r][2],
                        w = r == 0 ? s : i[r][3],
                        v = i[r][0],
                        u = i[r][1];
                    h[r] = y == v ? Infinity : 0;
                    g[r] = Math.abs(y == v ? u - w : v - y);
                    x += g[r]
                }
                var q = 0;
                for (var r = 0; r < i.length; r++) {
                    a[r] = g[r] / x;
                    l[r] = [q, (q += (g[r] / x))]
                }
            },
            o = function () {
                m.push(i.length);
                for (var q = 0; q < i.length; q++) {
                    m.push(i[q][0]);
                    m.push(i[q][1])
                }
            },
            e = function (r, z, w, v, s, q) {
                var u = i.length == 0 ? w : i[i.length - 1][0];
                var t = i.length == 0 ? v : i[i.length - 1][1];
                i.push([r, z, u, t])
            },
            k = function (s) {
                var q = l.length - 1,
                    r = 0;
                for (var t = 0; t < l.length; t++) {
                    if (l[t][1] >= s) {
                        q = t;
                        r = (s - l[t][0]) / a[t];
                        break
                    }
                }
                return {
                    segment: i[q],
                    proportion: r,
                    index: q
                }
            };
        this.compute = function (Q, v, P, r, q, H) {
            i = [];
            h = [];
            a = [];
            g = [];
            segmentProportionals = [];
            d = v[0] < Q[0];
            c = v[1] < Q[1];
            var z = q || 1,
                u = (z / 2) + (b * 2),
                s = (z / 2) + (b * 2),
                M = P.orientation || P.getOrientation(),
                t = r.orientation || r.getOrientation(),
                F = d ? v[0] : Q[0],
                E = c ? v[1] : Q[1],
                G = Math.abs(v[0] - Q[0]) + 2 * u,
                L = Math.abs(v[1] - Q[1]) + 2 * s;
            if (G < H) {
                u += (H - G) / 2;
                G = H
            }
            if (L < H) {
                s += (H - L) / 2;
                L = H
            }
            var J = d ? G - u : u,
                I = c ? L - s : s,
                S = d ? u : G - u,
                R = c ? s : L - s,
                D = J + (M[0] * b),
                C = I + (M[1] * b),
                B = S + (t[0] * b),
                A = R + (t[1] * b),
                N = D + ((B - D) / 2),
                K = C + ((A - C) / 2);
            F -= u;
            E -= s;
            m = [F, E, G, L, J, I, S, R], extraPoints = [];
            e(D, C, J, I, S, R);
            if (M[0] == 0) {
                var O = C < A;
                if (O) {
                    e(D, K, J, I, S, R);
                    e(N, K, J, I, S, R);
                    e(B, K, J, I, S, R)
                } else {
                    e(N, C, J, I, S, R);
                    e(N, A, J, I, S, R)
                }
            } else {
                var O = D < B;
                if (O) {
                    e(N, C, J, I, S, R);
                    e(N, K, J, I, S, R);
                    e(N, A, J, I, S, R)
                } else {
                    e(D, K, J, I, S, R);
                    e(B, K, J, I, S, R)
                }
            }
            e(B, A, J, I, S, R);
            e(S, R, J, I, S, R);
            o();
            p(J, I, S, R);
            return m
        };
        this.pointOnPath = function (q) {
            return n.pointAlongPathFrom(q, 0)
        };
        this.gradientAtPoint = function (q) {
            return h[k(q)["index"]]
        };
        this.pointAlongPathFrom = function (u, y) {
            var v = k(u),
                t = v.segment,
                x = v.proportion,
                r = g[v.index],
                q = h[v.index];
            var w = {
                x: q == Infinity ? t[2] : t[2] > t[0] ? t[0] + ((1 - x) * r) - y : t[2] + (x * r) + y,
                y: q == 0 ? t[3] : t[3] > t[1] ? t[1] + ((1 - x) * r) - y : t[3] + (x * r) + y,
                segmentInfo: v
            };
            return w
        };
        this.perpendicularToPathAt = function (t, u, z) {
            var v = n.pointAlongPathFrom(t, z);
            var s = h[v.segmentInfo.index];
            var r = Math.atan(-1 / s);
            var w = u / 2 * Math.sin(r);
            var q = u / 2 * Math.cos(r);
            return [{
                x: v.x + q,
                y: v.y + w
            }, {
                x: v.x - q,
                y: v.y - w
            }]
        }
    };
    jsPlumb.Endpoints.Dot = function (b) {
        var a = this;
        b = b || {};
        this.radius = b.radius || 10;
        this.defaultOffset = 0.5 * this.radius;
        this.defaultInnerRadius = this.radius / 3;
        this.compute = function (g, d, i, f) {
            var e = i.radius || a.radius;
            var c = g[0] - e;
            var h = g[1] - e;
            return [c, h, e * 2, e * 2, e]
        }
    };
    jsPlumb.Endpoints.Rectangle = function (b) {
        var a = this;
        b = b || {};
        this.width = b.width || 20;
        this.height = b.height || 20;
        this.compute = function (h, e, k, g) {
            var f = k.width || a.width;
            var d = k.height || a.height;
            var c = h[0] - (f / 2);
            var i = h[1] - (d / 2);
            return [c, i, f, d]
        }
    };
    jsPlumb.Endpoints.Image = function (d) {
        jsPlumb.DOMElementComponent.apply(this, arguments);
        var a = this,
            c = false;
        this.img = new Image();
        a.ready = false;
        this.img.onload = function () {
            a.ready = true
        };
        this.img.src = d.src || d.url;
        this.compute = function (g, e, h, f) {
            a.anchorPoint = g;
            if (a.ready) {
                return [g[0] - a.img.width / 2, g[1] - a.img.height / 2, a.img.width, a.img.height]
            } else {
                return [0, 0, 0, 0]
            }
        };
        a.canvas = document.createElement("img"), c = false;
        a.canvas.style.margin = 0;
        a.canvas.style.padding = 0;
        a.canvas.style.outline = 0;
        a.canvas.style.position = "absolute";
        a.canvas.className = jsPlumb.endpointClass;
        jsPlumb.appendElement(a.canvas, d.parent);
        a.attachListeners(a.canvas, a);
        var b = function (k, i, g) {
                if (!c) {
                    a.canvas.setAttribute("src", a.img.src);
                    c = true
                }
                var h = a.img.width,
                    f = a.img.height,
                    e = a.anchorPoint[0] - (h / 2),
                    l = a.anchorPoint[1] - (f / 2);
                jsPlumb.sizeCanvas(a.canvas, e, l, h, f)
            };
        this.paint = function (g, f, e) {
            if (a.ready) {
                b(g, f, e)
            } else {
                window.setTimeout(function () {
                    a.paint(g, f, e)
                }, 200)
            }
        }
    };
    jsPlumb.Endpoints.Blank = function () {
        jsPlumb.DOMElementComponent.apply(this, arguments);
        this.compute = function () {
            return [0, 0, 0, 0]
        };
        self.canvas = document.createElement("div");
        this.paint = function () {}
    };
    jsPlumb.Endpoints.Triangle = function (a) {
        a = a || {};
        a.width = a.width || 55;
        param.height = a.height || 55;
        this.width = a.width;
        this.height = a.height;
        this.compute = function (g, d, i, f) {
            var e = i.width || self.width;
            var c = i.height || self.height;
            var b = g[0] - (e / 2);
            var h = g[1] - (c / 2);
            return [b, h, e, c]
        }
    };
    jsPlumb.Overlays.Arrow = function (f) {
        f = f || {};
        var b = this;
        this.length = f.length || 20;
        this.width = f.width || 20;
        this.id = f.id;
        this.connection = f.connection;
        var e = (f.direction || 1) < 0 ? -1 : 1;
        var c = f.paintStyle || {
            lineWidth: 1
        };
        this.loc = f.location == null ? 0.5 : f.location;
        var a = f.foldback || 0.623;
        var d = function (g, i) {
                if (a == 0.5) {
                    return g.pointOnPath(i)
                } else {
                    var h = 0.5 - a;
                    return g.pointAlongPathFrom(i, e * b.length * h)
                }
            };
        this.computeMaxSize = function () {
            return b.width * 1.5
        };
        this.draw = function (k, q, w) {
            var y = k.pointAlongPathFrom(b.loc, e * (b.length / 2));
            var t = k.pointAlongPathFrom(b.loc, -1 * e * (b.length / 2)),
                B = t.x,
                A = t.y;
            var r = k.perpendicularToPathAt(b.loc, b.width, -1 * e * (b.length / 2));
            var i = d(k, b.loc);
            if (b.loc == 1) {
                var h = k.pointOnPath(b.loc);
                var v = (h.x - y.x) * e,
                    u = (h.y - y.y) * e;
                i.x += v;
                i.y += u;
                t.x += v;
                t.y += u;
                r[0].x += v;
                r[0].y += u;
                r[1].x += v;
                r[1].y += u;
                y.x += v;
                y.y += u
            }
            if (b.loc == 0) {
                var h = k.pointOnPath(b.loc);
                var s = a > 1 ? i : {
                    x: r[0].x + ((r[1].x - r[0].x) / 2),
                    y: r[0].y + ((r[1].y - r[0].y) / 2)
                };
                var v = (h.x - s.x) * e,
                    u = (h.y - s.y) * e;
                i.x += v;
                i.y += u;
                t.x += v;
                t.y += u;
                r[0].x += v;
                r[0].y += u;
                r[1].x += v;
                r[1].y += u;
                y.x += v;
                y.y += u
            }
            var o = Math.min(y.x, r[0].x, r[1].x);
            var n = Math.max(y.x, r[0].x, r[1].x);
            var m = Math.min(y.y, r[0].y, r[1].y);
            var l = Math.max(y.y, r[0].y, r[1].y);
            var z = {
                hxy: y,
                tail: r,
                cxy: i
            },
                x = c.strokeStyle || q.strokeStyle,
                p = c.fillStyle || q.strokeStyle,
                g = c.lineWidth || q.lineWidth;
            b.paint(k, z, g, x, p, w);
            return [o, n, m, l]
        }
    };
    jsPlumb.Overlays.PlainArrow = function (b) {
        b = b || {};
        var a = jsPlumb.extend(b, {
            foldback: 1
        });
        jsPlumb.Overlays.Arrow.call(this, a)
    };
    jsPlumb.Overlays.Diamond = function (c) {
        c = c || {};
        var a = c.length || 40;
        var b = jsPlumb.extend(c, {
            length: a / 2,
            foldback: 2
        });
        jsPlumb.Overlays.Arrow.call(this, b)
    };
    jsPlumb.Overlays.Label = function (d) {
        jsPlumb.DOMElementComponent.apply(this, arguments);
        this.labelStyle = d.labelStyle || jsPlumb.Defaults.LabelStyle;
        this.labelStyle.font = this.labelStyle.font || "12px sans-serif";
        this.label = d.label;
        this.connection = d.connection;
        this.id = d.id;
        var k = this;
        var h = null,
            e = null,
            c = null,
            b = null;
        this.location = d.location || 0.5;
        this.cachedDimensions = null;
        var i = false,
            c = null,
            a = document.createElement("div");
        a.style.position = "absolute";
        a.style.textAlign = "center";
        a.style.cursor = "pointer";
        a.style.font = k.labelStyle.font;
        a.style.color = k.labelStyle.color || "black";
        if (k.labelStyle.fillStyle) {
            a.style.background = k.labelStyle.fillStyle
        }
        if (k.labelStyle.borderWidth > 0) {
            var g = k.labelStyle.borderStyle ? k.labelStyle.borderStyle : "black";
            a.style.border = k.labelStyle.borderWidth + "px solid " + g
        }
        if (k.labelStyle.padding) {
            a.style.padding = k.labelStyle.padding
        }
        var f = d._jsPlumb.overlayClass + " " + (k.labelStyle.cssClass ? k.labelStyle.cssClass : d.cssClass ? d.cssClass : "");
        a.className = f;
        jsPlumb.appendElement(a, d.connection.parent);
        jsPlumb.getId(a);
        k.attachListeners(a, k);
        this.paint = function (l, n, m) {
            if (!i) {
                l.appendDisplayElement(a);
                k.attachListeners(a, l);
                i = true
            }
            a.style.left = (m[0] + n.minx) + "px";
            a.style.top = (m[1] + n.miny) + "px"
        };
        this.getTextDimensions = function (l) {
            c = typeof k.label == "function" ? k.label(k) : k.label;
            a.innerHTML = c.replace(/\r\n/g, "<br/>");
            var n = jsPlumb.CurrentLibrary.getElementObject(a),
                m = jsPlumb.CurrentLibrary.getSize(n);
            return {
                width: m[0],
                height: m[1]
            }
        };
        this.computeMaxSize = function (l) {
            var m = k.getTextDimensions(l);
            return m.width ? Math.max(m.width, m.height) * 1.5 : 0
        };
        this.draw = function (n, p, o) {
            var r = k.getTextDimensions(n);
            if (r.width != null) {
                var q = n.pointOnPath(k.location);
                var m = q.x - (r.width / 2);
                var l = q.y - (r.height / 2);
                k.paint(n, {
                    minx: m,
                    miny: l,
                    td: r,
                    cxy: q
                }, o);
                return [m, m + r.width, l, l + r.height]
            } else {
                return [0, 0, 0, 0]
            }
        }
    }
})();
(function () {
    var h = {
        "stroke-linejoin": "joinstyle",
        joinstyle: "joinstyle",
        endcap: "endcap",
        miterlimit: "miterlimit"
    };
    if (document.createStyleSheet) {
        document.createStyleSheet().addRule(".jsplumb_vml", "behavior:url(#default#VML);position:absolute;");
        document.createStyleSheet().addRule("jsplumb\\:textbox", "behavior:url(#default#VML);position:absolute;");
        document.createStyleSheet().addRule("jsplumb\\:oval", "behavior:url(#default#VML);position:absolute;");
        document.createStyleSheet().addRule("jsplumb\\:rect", "behavior:url(#default#VML);position:absolute;");
        document.createStyleSheet().addRule("jsplumb\\:stroke", "behavior:url(#default#VML);position:absolute;");
        document.createStyleSheet().addRule("jsplumb\\:shape", "behavior:url(#default#VML);position:absolute;");
        document.namespaces.add("jsplumb", "urn:schemas-microsoft-com:vml")
    }
    var c = 1000,
        d = function (p, q) {
            for (var n in q) {
                p[n] = q[n]
            }
        },
        m = function (n, q, r) {
            r = r || {};
            var p = document.createElement("jsplumb:" + n);
            p.className = (r["class"] ? r["class"] + " " : "") + "jsplumb_vml";
            l(p, q);
            d(p, r);
            return p
        },
        l = function (p, n) {
            p.style.left = n[0] + "px";
            p.style.top = n[1] + "px";
            p.style.width = n[2] + "px";
            p.style.height = n[3] + "px";
            p.style.position = "absolute"
        },
        g = function (n) {
            return Math.floor(n * c)
        },
        a = function (p, n) {
            var v = p,
                u = function (o) {
                    return o.length == 1 ? "0" + o : o
                },
                q = function (o) {
                    return u(Number(o).toString(16))
                },
                r = /(rgb[a]?\()(.*)(\))/;
            if (p.match(r)) {
                var t = p.match(r)[2].split(",");
                v = "#" + q(t[0]) + q(t[1]) + q(t[2]);
                if (!n && t.length == 4) {
                    v = v + q(t[3])
                }
            }
            return v
        },
        f = function (s, r, p) {
            var u = {};
            if (r.strokeStyle) {
                u.stroked = "true";
                u.strokecolor = a(r.strokeStyle, true);
                u.strokeweight = r.lineWidth + "px"
            } else {
                u.stroked = "false"
            }
            if (r.fillStyle) {
                u.filled = "true";
                u.fillcolor = a(r.fillStyle, true)
            } else {
                u.filled = "false"
            }
            if (r.dashstyle) {
                if (p.strokeNode == null) {
                    p.strokeNode = m("stroke", [0, 0, 0, 0], {
                        dashstyle: r.dashstyle
                    });
                    s.appendChild(p.strokeNode)
                } else {
                    p.strokeNode.dashstyle = r.dashstyle
                }
            } else {
                if (r["stroke-dasharray"] && r.lineWidth) {
                    var o = r["stroke-dasharray"].indexOf(",") == -1 ? " " : ",",
                        t = r["stroke-dasharray"].split(o),
                        n = "";
                    for (var q = 0; q < t.length; q++) {
                        n += (Math.floor(t[q] / r.lineWidth) + o)
                    }
                    if (p.strokeNode == null) {
                        p.strokeNode = m("stroke", [0, 0, 0, 0], {
                            dashstyle: n
                        });
                        s.appendChild(p.strokeNode)
                    } else {
                        p.strokeNode.dashstyle = n
                    }
                }
            }
            d(s, u)
        },
        i = function () {
            jsPlumb.jsPlumbUIComponent.apply(this, arguments)
        },
        e = function (p) {
            var n = this;
            n.strokeNode = null;
            n.canvas = null;
            i.apply(this, arguments);
            clazz = n._jsPlumb.connectorClass + (p.cssClass ? (" " + p.cssClass) : "");
            this.paint = function (w, s, q) {
                if (s != null) {
                    var v = n.getPath(w),
                        u = {
                            path: v
                        };
                    if (s.outlineColor) {
                        var t = s.outlineWidth || 1,
                            r = s.lineWidth + (2 * t);
                        outlineStyle = {
                            strokeStyle: a(s.outlineColor),
                            lineWidth: r
                        };
                        if (n.bgCanvas == null) {
                            u["class"] = clazz;
                            u.coordsize = (w[2] * c) + "," + (w[3] * c);
                            n.bgCanvas = m("shape", w, u);
                            jsPlumb.appendElement(n.bgCanvas, p.parent);
                            l(n.bgCanvas, w);
                            o.push(n.bgCanvas)
                        } else {
                            u.coordsize = (w[2] * c) + "," + (w[3] * c);
                            l(n.bgCanvas, w);
                            d(n.bgCanvas, u)
                        }
                        f(n.bgCanvas, outlineStyle, n)
                    }
                    if (n.canvas == null) {
                        u["class"] = clazz;
                        u.coordsize = (w[2] * c) + "," + (w[3] * c);
                        n.canvas = m("shape", w, u);
                        jsPlumb.appendElement(n.canvas, p.parent);
                        o.push(n.canvas);
                        n.attachListeners(n.canvas, n)
                    } else {
                        u.coordsize = (w[2] * c) + "," + (w[3] * c);
                        l(n.canvas, w);
                        d(n.canvas, u)
                    }
                    f(n.canvas, s, n)
                }
            };
            var o = [n.canvas];
            this.getDisplayElements = function () {
                return o
            };
            this.appendDisplayElement = function (q) {
                n.canvas.parentNode.appendChild(q);
                o.push(q)
            }
        },
        k = function (p) {
            i.apply(this, arguments);
            var n = null,
                o = this;
            o.canvas = document.createElement("div");
            o.canvas.style.position = "absolute";
            jsPlumb.appendElement(o.canvas, p.parent);
            this.paint = function (t, r, q) {
                var s = {};
                jsPlumb.sizeCanvas(o.canvas, t[0], t[1], t[2], t[3]);
                if (n == null) {
                    s["class"] = jsPlumb.endpointClass;
                    n = o.getVml([0, 0, t[2], t[3]], s, q);
                    o.canvas.appendChild(n);
                    o.attachListeners(n, o)
                } else {
                    l(n, [0, 0, t[2], t[3]]);
                    d(n, s)
                }
                f(n, r)
            }
        };
    jsPlumb.Connectors.vml.Bezier = function () {
        jsPlumb.Connectors.Bezier.apply(this, arguments);
        e.apply(this, arguments);
        this.getPath = function (n) {
            return "m" + g(n[4]) + "," + g(n[5]) + " c" + g(n[8]) + "," + g(n[9]) + "," + g(n[10]) + "," + g(n[11]) + "," + g(n[6]) + "," + g(n[7]) + " e"
        }
    };
    jsPlumb.Connectors.vml.Straight = function () {
        jsPlumb.Connectors.Straight.apply(this, arguments);
        e.apply(this, arguments);
        this.getPath = function (n) {
            return "m" + g(n[4]) + "," + g(n[5]) + " l" + g(n[6]) + "," + g(n[7]) + " e"
        }
    };
    jsPlumb.Connectors.vml.Flowchart = function () {
        jsPlumb.Connectors.Flowchart.apply(this, arguments);
        e.apply(this, arguments);
        this.getPath = function (o) {
            var q = "m " + g(o[4]) + "," + g(o[5]) + " l";
            for (var n = 0; n < o[8]; n++) {
                q = q + " " + g(o[9 + (n * 2)]) + "," + g(o[10 + (n * 2)])
            }
            q = q + " " + g(o[6]) + "," + g(o[7]) + " e";
            return q
        }
    };
    jsPlumb.Endpoints.vml.Dot = function () {
        jsPlumb.Endpoints.Dot.apply(this, arguments);
        k.apply(this, arguments);
        this.getVml = function (o, p, n) {
            return m("oval", o, p)
        }
    };
    jsPlumb.Endpoints.vml.Rectangle = function () {
        jsPlumb.Endpoints.Rectangle.apply(this, arguments);
        k.apply(this, arguments);
        this.getVml = function (o, p, n) {
            return m("rect", o, p)
        }
    };
    jsPlumb.Endpoints.vml.Image = jsPlumb.Endpoints.Image;
    jsPlumb.Endpoints.vml.Blank = jsPlumb.Endpoints.Blank;
    jsPlumb.Overlays.vml.Label = jsPlumb.Overlays.Label;
    var b = function (s, q) {
            s.apply(this, q);
            i.apply(this, arguments);
            var o = this,
                p = null,
                r = null;
            var n = function (u, t) {
                    return "m " + g(u.hxy.x) + "," + g(u.hxy.y) + " l " + g(u.tail[0].x) + "," + g(u.tail[0].y) + " " + g(u.cxy.x) + "," + g(u.cxy.y) + " " + g(u.tail[1].x) + "," + g(u.tail[1].y) + " x e"
                };
            this.paint = function (x, C, B, D, H, G) {
                var u = {};
                if (D) {
                    u.stroked = "true";
                    u.strokecolor = a(D, true)
                }
                if (B) {
                    u.strokeweight = B + "px"
                }
                if (H) {
                    u.filled = "true";
                    u.fillcolor = H
                }
                var t = Math.min(C.hxy.x, C.tail[0].x, C.tail[1].x, C.cxy.x),
                    F = Math.min(C.hxy.y, C.tail[0].y, C.tail[1].y, C.cxy.y),
                    y = Math.max(C.hxy.x, C.tail[0].x, C.tail[1].x, C.cxy.x),
                    v = Math.max(C.hxy.y, C.tail[0].y, C.tail[1].y, C.cxy.y),
                    E = Math.abs(y - t),
                    A = Math.abs(v - F),
                    z = [t, F, E, A];
                u.path = n(C, G);
                u.coordsize = (G[2] * c) + "," + (G[3] * c);
                z[0] = G[0];
                z[1] = G[1];
                z[2] = G[2];
                z[3] = G[3];
                if (p == null) {
                    p = m("shape", z, u);
                    x.appendDisplayElement(p);
                    o.attachListeners(p, x)
                } else {
                    l(p, z);
                    d(p, u)
                }
            }
        };
    jsPlumb.Overlays.vml.Arrow = function () {
        b.apply(this, [jsPlumb.Overlays.Arrow, arguments])
    };
    jsPlumb.Overlays.vml.PlainArrow = function () {
        b.apply(this, [jsPlumb.Overlays.PlainArrow, arguments])
    };
    jsPlumb.Overlays.vml.Diamond = function () {
        b.apply(this, [jsPlumb.Overlays.Diamond, arguments])
    }
})();
(function () {
    var i = {
        "stroke-linejoin": "stroke-linejoin",
        joinstyle: "stroke-linejoin",
        "stroke-dashoffset": "stroke-dashoffset"
    };
    var h = {
        svg: "http://www.w3.org/2000/svg",
        xhtml: "http://www.w3.org/1999/xhtml"
    },
        d = function (r, p) {
            for (var q in p) {
                r.setAttribute(q, "" + p[q])
            }
        },
        o = function (q, p) {
            var r = document.createElementNS(h.svg, q);
            p = p || {};
            p.version = "1.1";
            p.xmnls = h.xhtml;
            d(r, p);
            return r
        },
        m = function (p) {
            return "position:absolute;left:" + p[0] + "px;top:" + p[1] + "px"
        },
        a = function (q, p) {
            var w = q,
                v = function (s) {
                    return s.length == 1 ? "0" + s : s
                },
                r = function (s) {
                    return v(Number(s).toString(16))
                },
                t = /(rgb[a]?\()(.*)(\))/;
            if (q.match(t)) {
                var u = q.match(t)[2].split(",");
                w = "#" + r(u[0]) + r(u[1]) + r(u[2]);
                if (!p && u.length == 4) {
                    w = w + r(u[3])
                }
            }
            return w
        },
        b = function (q) {
            for (var p = 0; p < q.childNodes.length; p++) {
                if (q.childNodes[p].tagName == "linearGradient" || q.childNodes[p].tagName == "radialGradient") {
                    q.removeChild(q.childNodes[p])
                }
            }
        },
        l = function (z, v, r, p) {
            var t = "jsplumb_gradient_" + (new Date()).getTime();
            b(z);
            if (!r.gradient.offset) {
                var x = o("linearGradient", {
                    id: t
                });
                z.appendChild(x)
            } else {
                var x = o("radialGradient", {
                    id: t
                });
                z.appendChild(x)
            }
            for (var w = 0; w < r.gradient.stops.length; w++) {
                var u = w;
                if (p.length == 8) {
                    u = p[4] < p[6] ? w : r.gradient.stops.length - 1 - w
                } else {
                    u = p[4] < p[6] ? r.gradient.stops.length - 1 - w : w
                }
                var y = a(r.gradient.stops[u][1], true);
                var A = o("stop", {
                    offset: Math.floor(r.gradient.stops[w][0] * 100) + "%",
                    "stop-color": y
                });
                x.appendChild(A)
            }
            var q = r.strokeStyle ? "stroke" : "fill";
            v.setAttribute("style", q + ":url(#" + t + ")")
        },
        f = function (t, v, s, u) {
            if (s.gradient) {
                l(t, v, s, u)
            } else {
                b(t);
                v.setAttribute("style", "")
            }
            v.setAttribute("fill", s.fillStyle ? a(s.fillStyle, true) : "none");
            v.setAttribute("stroke", s.strokeStyle ? a(s.strokeStyle, true) : "none");
            if (s.lineWidth) {
                v.setAttribute("stroke-width", s.lineWidth)
            }
            if (s["stroke-dasharray"]) {
                v.setAttribute("stroke-dasharray", s["stroke-dasharray"])
            }
            if (s.dashstyle && s.lineWidth) {
                var q = s.dashstyle.indexOf(",") == -1 ? " " : ",",
                    w = s.dashstyle.split(q),
                    p = "";
                w.forEach(function (x) {
                    p += (Math.floor(x * s.lineWidth) + q)
                });
                v.setAttribute("stroke-dasharray", p)
            }
            for (var r in i) {
                if (s[r]) {
                    v.setAttribute(i[r], s[r])
                }
            }
        },
        g = function (s) {
            var p = /([0-9].)(p[xt])\s(.*)/;
            var q = s.match(p);
            return {
                size: q[1] + q[2],
                font: q[3]
            }
        };
    var k = function (p, t, u) {
            var q = this;
            u = u || "all";
            jsPlumb.jsPlumbUIComponent.apply(this, t);
            q.canvas = null, q.path = null, q.svg = null;
            this.setHover = function () {};
            q.canvas = document.createElement("div");
            q.canvas.style.position = "absolute";
            jsPlumb.sizeCanvas(q.canvas, 0, 0, 1, 1);
            var s = p + " " + (t[0].cssClass || "");
            q.svg = o("svg", {
                style: "",
                width: 0,
                height: 0,
                "pointer-events": u,
                "class": s
            });
            jsPlumb.appendElement(q.canvas, t[0]["parent"]);
            q.canvas.appendChild(q.svg);
            var r = [q.canvas];
            this.getDisplayElements = function () {
                return r
            };
            this.appendDisplayElement = function (v) {
                r.push(v)
            };
            this.paint = function (x, w, v) {
                if (w != null) {
                    jsPlumb.sizeCanvas(q.canvas, x[0], x[1], x[2], x[3]);
                    d(q.svg, {
                        style: m([0, 0, x[2], x[3]]),
                        width: x[2],
                        height: x[3]
                    });
                    q._paint.apply(this, arguments)
                }
            }
        };
    var e = function (q) {
            var p = this;
            k.apply(this, [q._jsPlumb.connectorClass, arguments, "none"]);
            this._paint = function (x, t) {
                var w = p.getPath(x),
                    r = {
                        d: w
                    },
                    v = null;
                r["pointer-events"] = "all";
                if (t.outlineColor) {
                    var u = t.outlineWidth || 1,
                        s = t.lineWidth + (2 * u);
                    v = {
                        strokeStyle: a(t.outlineColor),
                        lineWidth: s
                    };
                    if (p.bgPath == null) {
                        p.bgPath = o("path", r);
                        p.svg.appendChild(p.bgPath);
                        p.attachListeners(p.bgPath, p)
                    } else {
                        d(p.bgPath, r)
                    }
                    f(p.svg, p.bgPath, v, x)
                }
                if (p.path == null) {
                    p.path = o("path", r);
                    p.svg.appendChild(p.path);
                    p.attachListeners(p.path, p)
                } else {
                    d(p.path, r)
                }
                f(p.svg, p.path, t, x)
            }
        };
    jsPlumb.Connectors.svg.Bezier = function (p) {
        jsPlumb.Connectors.Bezier.apply(this, arguments);
        e.apply(this, arguments);
        this.getPath = function (q) {
            return "M " + q[4] + " " + q[5] + " C " + q[8] + " " + q[9] + " " + q[10] + " " + q[11] + " " + q[6] + " " + q[7]
        }
    };
    jsPlumb.Connectors.svg.Straight = function (p) {
        jsPlumb.Connectors.Straight.apply(this, arguments);
        e.apply(this, arguments);
        this.getPath = function (q) {
            return "M " + q[4] + " " + q[5] + " L " + q[6] + " " + q[7]
        }
    };
    jsPlumb.Connectors.svg.Flowchart = function () {
        var p = this;
        jsPlumb.Connectors.Flowchart.apply(this, arguments);
        e.apply(this, arguments);
        this.getPath = function (r) {
            var s = "M " + r[4] + "," + r[5];
            for (var q = 0; q < r[8]; q++) {
                s = s + " L " + r[9 + (q * 2)] + " " + r[10 + (q * 2)]
            }
            s = s + " " + r[6] + "," + r[7];
            return s
        }
    };
    var n = function (q) {
            var p = this;
            k.apply(this, [q._jsPlumb.endpointClass, arguments, "all"]);
            this._paint = function (u, t) {
                var r = jsPlumb.extend({}, t);
                if (r.outlineColor) {
                    r.strokeWidth = r.outlineWidth;
                    r.strokeStyle = a(r.outlineColor, true)
                }
                if (p.node == null) {
                    p.node = p.makeNode(u, r);
                    p.svg.appendChild(p.node);
                    p.attachListeners(p.node, p)
                }
                f(p.svg, p.node, r, u);
                m(p.node, u)
            }
        };
    jsPlumb.Endpoints.svg.Dot = function () {
        jsPlumb.Endpoints.Dot.apply(this, arguments);
        n.apply(this, arguments);
        this.makeNode = function (q, p) {
            return o("circle", {
                cx: q[2] / 2,
                cy: q[3] / 2,
                r: q[2] / 2
            })
        }
    };
    jsPlumb.Endpoints.svg.Rectangle = function () {
        jsPlumb.Endpoints.Rectangle.apply(this, arguments);
        n.apply(this, arguments);
        this.makeNode = function (q, p) {
            return o("rect", {
                width: q[2],
                height: q[3]
            })
        }
    };
    jsPlumb.Endpoints.svg.Image = jsPlumb.Endpoints.Image;
    jsPlumb.Endpoints.svg.Blank = jsPlumb.Endpoints.Blank;
    jsPlumb.Overlays.svg.Label = jsPlumb.Overlays.Label;
    var c = function (t, r) {
            t.apply(this, r);
            jsPlumb.jsPlumbUIComponent.apply(this, r);
            var p = this,
                s = null;
            this.paint = function (v, x, u, y, w) {
                if (s == null) {
                    s = o("path");
                    v.svg.appendChild(s);
                    p.attachListeners(s, v);
                    p.attachListeners(s, p)
                }
                d(s, {
                    d: q(x),
                    stroke: y ? y : null,
                    fill: w ? w : null
                })
            };
            var q = function (u) {
                    return "M" + u.hxy.x + "," + u.hxy.y + " L" + u.tail[0].x + "," + u.tail[0].y + " L" + u.cxy.x + "," + u.cxy.y + " L" + u.tail[1].x + "," + u.tail[1].y + " L" + u.hxy.x + "," + u.hxy.y
                }
        };
    jsPlumb.Overlays.svg.Arrow = function () {
        c.apply(this, [jsPlumb.Overlays.Arrow, arguments])
    };
    jsPlumb.Overlays.svg.PlainArrow = function () {
        c.apply(this, [jsPlumb.Overlays.PlainArrow, arguments])
    };
    jsPlumb.Overlays.svg.Diamond = function () {
        c.apply(this, [jsPlumb.Overlays.Diamond, arguments])
    }
})();
(function () {
    var o = null,
        b = function (t, u) {
            return jsPlumb.CurrentLibrary.getAttribute(s(t), u)
        },
        c = function (u, v, t) {
            jsPlumb.CurrentLibrary.setAttribute(s(u), v, t)
        },
        q = function (u, t) {
            jsPlumb.CurrentLibrary.addClass(s(u), t)
        },
        f = function (u, t) {
            return jsPlumb.CurrentLibrary.hasClass(s(u), t)
        },
        h = function (u, t) {
            jsPlumb.CurrentLibrary.removeClass(s(u), t)
        },
        s = function (t) {
            return jsPlumb.CurrentLibrary.getElementObject(t)
        },
        m = function (t) {
            return jsPlumb.CurrentLibrary.getOffset(s(t))
        },
        a = function (t) {
            return jsPlumb.CurrentLibrary.getSize(s(t))
        },
        e = function (t) {
            return jsPlumb.CurrentLibrary.getPageXY(t)
        },
        i = function (t) {
            return jsPlumb.CurrentLibrary.getClientXY(t)
        },
        g = function (t, u) {
            jsPlumb.CurrentLibrary.setOffset(t, u)
        };
    var n = function () {
            var v = this;
            v.overlayPlacements = [];
            jsPlumb.jsPlumbUIComponent.apply(this, arguments);
            jsPlumb.EventGenerator.apply(this, arguments);
            this._over = function (C) {
                var E = m(s(v.canvas)),
                    G = e(C),
                    z = G[0] - E.left,
                    F = G[1] - E.top;
                if (z > 0 && F > 0 && z < v.canvas.width && F < v.canvas.height) {
                    for (var A = 0; A < v.overlayPlacements.length; A++) {
                        var B = v.overlayPlacements[A];
                        if (B && (B[0] <= z && B[1] >= z && B[2] <= F && B[3] >= F)) {
                            return true
                        }
                    }
                    var D = v.canvas.getContext("2d").getImageData(parseInt(z), parseInt(F), 1, 1);
                    return D.data[0] != 0 || D.data[1] != 0 || D.data[2] != 0 || D.data[3] != 0
                }
                return false
            };
            var u = false;
            var t = false,
                y = null,
                x = false;
            var w = function (A, z) {
                    return A != null && f(A, z)
                };
            this.mousemove = function (C) {
                var E = e(C),
                    B = i(C),
                    A = document.elementFromPoint(B[0], B[1]),
                    D = w(A, "_jsPlumb_overlay");
                var z = o == null && (w(A, "_jsPlumb_endpoint") || w(A, "_jsPlumb_connector"));
                if (!u && z && v._over(C)) {
                    u = true;
                    v.fire("mouseenter", v, C);
                    return true
                } else {
                    if (u && (!v._over(C) || !z) && !D) {
                        u = false;
                        v.fire("mouseexit", v, C)
                    }
                }
                v.fire("mousemove", v, C)
            };
            this.click = function (z) {
                if (u && v._over(z) && !x) {
                    v.fire("click", v, z)
                }
                x = false
            };
            this.dblclick = function (z) {
                if (u && v._over(z) && !x) {
                    v.fire("dblclick", v, z)
                }
                x = false
            };
            this.mousedown = function (z) {
                if (v._over(z) && !t) {
                    t = true;
                    y = m(s(v.canvas));
                    v.fire("mousedown", v, z)
                }
            };
            this.mouseup = function (z) {
                t = false;
                v.fire("mouseup", v, z)
            }
        };
    var p = function (u) {
            var t = document.createElement("canvas");
            jsPlumb.appendElement(t, u.parent);
            t.style.position = "absolute";
            if (u["class"]) {
                t.className = u["class"]
            }
            u._jsPlumb.getId(t, u.uuid);
            return t
        };
    var d = jsPlumb.CanvasConnector = function (x) {
            n.apply(this, arguments);
            var t = function (B, z) {
                    u.ctx.save();
                    jsPlumb.extend(u.ctx, z);
                    if (z.gradient) {
                        var A = u.createGradient(B, u.ctx);
                        for (var y = 0; y < z.gradient.stops.length; y++) {
                            A.addColorStop(z.gradient.stops[y][0], z.gradient.stops[y][1])
                        }
                        u.ctx.strokeStyle = A
                    }
                    u._paint(B);
                    u.ctx.restore()
                };
            var u = this,
                w = u._jsPlumb.connectorClass + " " + (x.cssClass || "");
            u.canvas = p({
                "class": w,
                _jsPlumb: u._jsPlumb,
                parent: x.parent
            });
            u.ctx = u.canvas.getContext("2d");
            var v = [u.canvas];
            this.getDisplayElements = function () {
                return v
            };
            this.appendDisplayElement = function (y) {
                v.push(y)
            };
            u.paint = function (C, z) {
                if (z != null) {
                    jsPlumb.sizeCanvas(u.canvas, C[0], C[1], C[2], C[3]);
                    if (z.outlineColor != null) {
                        var B = z.outlineWidth || 1,
                            y = z.lineWidth + (2 * B);
                        var A = {
                            strokeStyle: z.outlineColor,
                            lineWidth: y
                        };
                        t(C, A)
                    }
                    t(C, z)
                }
            }
        };
    var l = function (v) {
            var t = this;
            n.apply(this, arguments);
            var u = t._jsPlumb.endpointClass + " " + (v.cssClass || "");
            t.canvas = p({
                "class": u,
                _jsPlumb: t._jsPlumb,
                parent: v.parent
            });
            t.ctx = t.canvas.getContext("2d");
            this.paint = function (B, y, w) {
                jsPlumb.sizeCanvas(t.canvas, B[0], B[1], B[2], B[3]);
                if (y.outlineColor != null) {
                    var A = y.outlineWidth || 1,
                        x = y.lineWidth + (2 * A);
                    var z = {
                        strokeStyle: y.outlineColor,
                        lineWidth: x
                    }
                }
                t._paint.apply(this, arguments)
            }
        };
    jsPlumb.Endpoints.canvas.Dot = function (w) {
        var v = this;
        jsPlumb.Endpoints.Dot.apply(this, arguments);
        l.apply(this, arguments);
        var u = function (x) {
                try {
                    return parseInt(x)
                } catch (y) {
                    if (x.substring(x.length - 1) == "%") {
                        return parseInt(x.substring(0, x - 1))
                    }
                }
            };
        var t = function (z) {
                var x = v.defaultOffset,
                    y = v.defaultInnerRadius;
                z.offset && (x = u(z.offset));
                z.innerRadius && (y = u(z.innerRadius));
                return [x, y]
            };
        this._paint = function (F, y, C) {
            if (y != null) {
                var G = v.canvas.getContext("2d"),
                    z = C.getOrientation();
                jsPlumb.extend(G, y);
                if (y.gradient) {
                    var A = t(y.gradient),
                        D = z[1] == 1 ? A[0] * -1 : A[0],
                        x = z[0] == 1 ? A[0] * -1 : A[0],
                        E = G.createRadialGradient(F[4], F[4], F[4], F[4] + x, F[4] + D, A[1]);
                    for (var B = 0; B < y.gradient.stops.length; B++) {
                        E.addColorStop(y.gradient.stops[B][0], y.gradient.stops[B][1])
                    }
                    G.fillStyle = E
                }
                G.beginPath();
                G.arc(F[4], F[4], F[4], 0, Math.PI * 2, true);
                G.closePath();
                if (y.fillStyle || y.gradient) {
                    G.fill()
                }
                if (y.strokeStyle) {
                    G.stroke()
                }
            }
        }
    };
    jsPlumb.Endpoints.canvas.Rectangle = function (u) {
        var t = this;
        jsPlumb.Endpoints.Rectangle.apply(this, arguments);
        l.apply(this, arguments);
        this._paint = function (C, w, A) {
            var F = t.canvas.getContext("2d"),
                y = A.getOrientation();
            jsPlumb.extend(F, w);
            if (w.gradient) {
                var E = y[1] == 1 ? C[3] : y[1] == 0 ? C[3] / 2 : 0;
                var D = y[1] == -1 ? C[3] : y[1] == 0 ? C[3] / 2 : 0;
                var x = y[0] == 1 ? C[2] : y[0] == 0 ? C[2] / 2 : 0;
                var v = y[0] == -1 ? C[2] : y[0] == 0 ? C[2] / 2 : 0;
                var B = F.createLinearGradient(x, E, v, D);
                for (var z = 0; z < w.gradient.stops.length; z++) {
                    B.addColorStop(w.gradient.stops[z][0], w.gradient.stops[z][1])
                }
                F.fillStyle = B
            }
            F.beginPath();
            F.rect(0, 0, C[2], C[3]);
            F.closePath();
            if (w.fillStyle || w.gradient) {
                F.fill()
            }
            if (w.strokeStyle) {
                F.stroke()
            }
        }
    };
    jsPlumb.Endpoints.canvas.Triangle = function (u) {
        var t = this;
        jsPlumb.Endpoints.Triangle.apply(this, arguments);
        l.apply(this, arguments);
        this._paint = function (D, v, B) {
            var w = D[2],
                G = D[3],
                F = D[0],
                E = D[1];
            var H = t.canvas.getContext("2d");
            var C = 0,
                A = 0,
                z = 0;
            if (orientation[0] == 1) {
                C = w;
                A = G;
                z = 180
            }
            if (orientation[1] == -1) {
                C = w;
                z = 90
            }
            if (orientation[1] == 1) {
                A = G;
                z = -90
            }
            H.fillStyle = v.fillStyle;
            H.translate(C, A);
            H.rotate(z * Math.PI / 180);
            H.beginPath();
            H.moveTo(0, 0);
            H.lineTo(w / 2, G / 2);
            H.lineTo(0, G);
            H.closePath();
            if (v.fillStyle || v.gradient) {
                H.fill()
            }
            if (v.strokeStyle) {
                H.stroke()
            }
        }
    };
    jsPlumb.Endpoints.canvas.Image = jsPlumb.Endpoints.Image;
    jsPlumb.Endpoints.canvas.Blank = jsPlumb.Endpoints.Blank;
    jsPlumb.Connectors.canvas.Bezier = function () {
        var t = this;
        jsPlumb.Connectors.Bezier.apply(this, arguments);
        d.apply(this, arguments);
        this._paint = function (u) {
            t.ctx.beginPath();
            t.ctx.moveTo(u[4], u[5]);
            t.ctx.bezierCurveTo(u[8], u[9], u[10], u[11], u[6], u[7]);
            t.ctx.stroke()
        };
        this.createGradient = function (w, u, v) {
            return t.ctx.createLinearGradient(w[6], w[7], w[4], w[5])
        }
    };
    jsPlumb.Connectors.canvas.Straight = function () {
        var t = this;
        jsPlumb.Connectors.Straight.apply(this, arguments);
        d.apply(this, arguments);
        this._paint = function (u) {
            t.ctx.beginPath();
            t.ctx.moveTo(u[4], u[5]);
            t.ctx.lineTo(u[6], u[7]);
            t.ctx.stroke()
        };
        this.createGradient = function (v, u) {
            return u.createLinearGradient(v[4], v[5], v[6], v[7])
        }
    };
    jsPlumb.Connectors.canvas.Flowchart = function () {
        var t = this;
        jsPlumb.Connectors.Flowchart.apply(this, arguments);
        d.apply(this, arguments);
        this._paint = function (v) {
            t.ctx.beginPath();
            t.ctx.moveTo(v[4], v[5]);
            for (var u = 0; u < v[8]; u++) {
                t.ctx.lineTo(v[9 + (u * 2)], v[10 + (u * 2)])
            }
            t.ctx.lineTo(v[6], v[7]);
            t.ctx.stroke()
        };
        this.createGradient = function (v, u) {
            return u.createLinearGradient(v[4], v[5], v[6], v[7])
        }
    };
    jsPlumb.Overlays.canvas.Label = jsPlumb.Overlays.Label;
    var k = function () {
            jsPlumb.jsPlumbUIComponent.apply(this, arguments)
        };
    var r = function (u, t) {
            u.apply(this, t);
            k.apply(this, arguments);
            this.paint = function (x, z, v, A, y) {
                var w = x.ctx;
                w.lineWidth = v;
                w.beginPath();
                w.moveTo(z.hxy.x, z.hxy.y);
                w.lineTo(z.tail[0].x, z.tail[0].y);
                w.lineTo(z.cxy.x, z.cxy.y);
                w.lineTo(z.tail[1].x, z.tail[1].y);
                w.lineTo(z.hxy.x, z.hxy.y);
                w.closePath();
                if (A) {
                    w.strokeStyle = A;
                    w.stroke()
                }
                if (y) {
                    w.fillStyle = y;
                    w.fill()
                }
            }
        };
    jsPlumb.Overlays.canvas.Arrow = function () {
        r.apply(this, [jsPlumb.Overlays.Arrow, arguments])
    };
    jsPlumb.Overlays.canvas.PlainArrow = function () {
        r.apply(this, [jsPlumb.Overlays.PlainArrow, arguments])
    };
    jsPlumb.Overlays.canvas.Diamond = function () {
        r.apply(this, [jsPlumb.Overlays.Diamond, arguments])
    }
})();
(function (a) {
    jsPlumb.CurrentLibrary = {
        addClass: function (c, b) {
            c.addClass(b)
        },
        animate: function (d, c, b) {
            d.animate(c, b)
        },
        appendElement: function (c, b) {
            jsPlumb.CurrentLibrary.getElementObject(b).append(c)
        },
        bind: function (b, c, d) {
            b = jsPlumb.CurrentLibrary.getElementObject(b);
            b.bind(c, d)
        },
        dragEvents: {
            start: "start",
            stop: "stop",
            drag: "drag",
            step: "step",
            over: "over",
            out: "out",
            drop: "drop",
            complete: "complete"
        },
        extend: function (c, b) {
            return a.extend(c, b)
        },
        getAttribute: function (b, c) {
            return b.attr(c)
        },
        getClientXY: function (b) {
            return [b.clientX, b.clientY]
        },
        getDocumentElement: function () {
            return document
        },
        getDragObject: function (b) {
            return b[1].draggable
        },
        getDragScope: function (b) {
            return b.draggable("option", "scope")
        },
        getElementObject: function (b) {
            return typeof (b) == "string" ? a("#" + b) : a(b)
        },
        getOffset: function (b) {
            return b.offset()
        },
        getPageXY: function (b) {
            return [b.pageX, b.pageY]
        },
        getParent: function (b) {
            return jsPlumb.CurrentLibrary.getElementObject(b).parent()
        },
        getScrollLeft: function (b) {
            return b.scrollLeft()
        },
        getScrollTop: function (b) {
            return b.scrollTop()
        },
        getSize: function (b) {
            return [b.outerWidth(), b.outerHeight()]
        },
        getUIPosition: function (c) {
            var d = c[1],
                b = d.offset;
            return b || d.absolutePosition
        },
        hasClass: function (c, b) {
            return c.hasClass(b)
        },
        initDraggable: function (c, b) {
            b.helper = null;
            b.scope = b.scope || jsPlumb.Defaults.Scope;
            c.draggable(b)
        },
        initDroppable: function (c, b) {
            b.scope = b.scope || jsPlumb.Defaults.Scope;
            c.droppable(b)
        },
        isAlreadyDraggable: function (b) {
            b = jsPlumb.CurrentLibrary.getElementObject(b);
            return b.hasClass("ui-draggable")
        },
        isDragSupported: function (c, b) {
            return c.draggable
        },
        isDropSupported: function (c, b) {
            return c.droppable
        },
        removeClass: function (c, b) {
            c.removeClass(b)
        },
        removeElement: function (b, c) {
            jsPlumb.CurrentLibrary.getElementObject(b).remove()
        },
        setAttribute: function (c, d, b) {
            c.attr(d, b)
        },
        setDraggable: function (c, b) {
            c.draggable("option", "disabled", !b)
        },
        setDragScope: function (c, b) {
            c.draggable("option", "scope", b)
        },
        setOffset: function (b, c) {
            jsPlumb.CurrentLibrary.getElementObject(b).offset(c)
        }
    };
    a(document).ready(jsPlumb.init)
})(jQuery);
(function () {
    if (typeof Math.sgn == "undefined") {
        Math.sgn = function (l) {
            return l == 0 ? 0 : l > 0 ? 1 : -1
        }
    }
    var b = {
        subtract: function (m, l) {
            return {
                x: m.x - l.x,
                y: m.y - l.y
            }
        },
        dotProduct: function (m, l) {
            return m.x * l.x + m.y * l.y
        },
        square: function (l) {
            return Math.sqrt(l.x * l.x + l.y * l.y)
        },
        scale: function (m, l) {
            return {
                x: m.x * l,
                y: m.y * l
            }
        }
    },
        d = Math.pow(2, -65),
        h = function (y, x) {
            for (var s = [], v = x.length - 1, r = 2 * v - 1, t = [], w = [], o = [], p = [], q = [
                [1, 0.6, 0.3, 0.1],
                [0.4, 0.6, 0.6, 0.4],
                [0.1, 0.3, 0.6, 1]
            ], u = 0; u <= v; u++) {
                t[u] = b.subtract(x[u], y)
            }
            for (u = 0; u <= v - 1; u++) {
                w[u] = b.subtract(x[u + 1], x[u]);
                w[u] = b.scale(w[u], 3)
            }
            for (u = 0; u <= v - 1; u++) {
                for (var n = 0; n <= v; n++) {
                    o[u] || (o[u] = []);
                    o[u][n] = b.dotProduct(w[u], t[n])
                }
            }
            for (u = 0; u <= r; u++) {
                p[u] || (p[u] = []);
                p[u].y = 0;
                p[u].x = parseFloat(u) / r
            }
            r = v - 1;
            for (t = 0; t <= v + r; t++) {
                w = Math.min(t, v);
                for (u = Math.max(0, t - r); u <= w; u++) {
                    j = t - u;
                    p[u + j].y += o[j][u] * q[j][u]
                }
            }
            v = x.length - 1;
            p = k(p, 2 * v - 1, s, 0);
            r = b.subtract(y, x[0]);
            o = b.square(r);
            for (u = q = 0; u < p; u++) {
                r = b.subtract(y, i(x, v, s[u], null, null));
                r = b.square(r);
                if (r < o) {
                    o = r;
                    q = s[u]
                }
            }
            r = b.subtract(y, x[v]);
            r = b.square(r);
            if (r < o) {
                o = r;
                q = 1
            }
            return {
                location: q,
                distance: o
            }
        },
        k = function (E, D, y, B) {
            var x = [],
                z = [],
                C = [],
                u = [],
                v = 0,
                w, A;
            A = Math.sgn(E[0].y);
            for (var t = 1; t <= D; t++) {
                w = Math.sgn(E[t].y);
                w != A && v++;
                A = w
            }
            switch (v) {
            case 0:
                return 0;
            case 1:
                if (B >= 64) {
                    y[0] = (E[0].x + E[D].x) / 2;
                    return 1
                }
                var s, r, p;
                v = E[0].y - E[D].y;
                w = E[D].x - E[0].x;
                A = E[0].x * E[D].y - E[D].x * E[0].y;
                t = max_distance_below = 0;
                for (r = 1; r < D; r++) {
                    p = v * E[r].x + w * E[r].y + A;
                    if (p > t) {
                        t = p
                    } else {
                        if (p < max_distance_below) {
                            max_distance_below = p
                        }
                    }
                }
                s = v;
                r = w;
                p = A - t;
                s = 0 * r - s * 1;
                s = 1 / s;
                t = (1 * p - r * 0) * s;
                s = v;
                r = w;
                p = A - max_distance_below;
                s = 0 * r - s * 1;
                s = 1 / s;
                v = (1 * p - r * 0) * s;
                if (Math.max(t, v) - Math.min(t, v) < d ? 1 : 0) {
                    C = E[D].x - E[0].x;
                    u = E[D].y - E[0].y;
                    y[0] = 0 + 1 * (C * (E[0].y - 0) - u * (E[0].x - 0)) * (1 / (C * 0 - u * 1));
                    return 1
                }
            }
            i(E, D, 0.5, x, z);
            E = k(x, D, C, B + 1);
            D = k(z, D, u, B + 1);
            for (B = 0; B < E; B++) {
                y[B] = C[B]
            }
            for (B = 0; B < D; B++) {
                y[B + E] = u[B]
            }
            return E + D
        },
        i = function (m, l, o, q, n) {
            for (var p = [
                []
            ], r = 0; r <= l; r++) {
                p[0][r] = m[r]
            }
            for (m = 1; m <= l; m++) {
                for (r = 0; r <= l - m; r++) {
                    p[m] || (p[m] = []);
                    p[m][r] || (p[m][r] = {});
                    p[m][r].x = (1 - o) * p[m - 1][r].x + o * p[m - 1][r + 1].x;
                    p[m][r].y = (1 - o) * p[m - 1][r].y + o * p[m - 1][r + 1].y
                }
            }
            if (q != null) {
                for (r = 0; r <= l; r++) {
                    q[r] = p[r][0]
                }
            }
            if (n != null) {
                for (r = 0; r <= l; r++) {
                    n[r] = p[l - r][r]
                }
            }
            return p[l][0]
        },
        g = {},
        c = function (u) {
            var t = g[u];
            if (!t) {
                t = [];
                var p = function (l) {
                        return function () {
                            return l
                        }
                    },
                    r = function () {
                        return function (l) {
                            return l
                        }
                    },
                    o = function () {
                        return function (l) {
                            return 1 - l
                        }
                    },
                    q = function (l) {
                        return function (w) {
                            for (var v = 1, x = 0; x < l.length; x++) {
                                v *= l[x](w)
                            }
                            return v
                        }
                    };
                t.push(new function () {
                    return function (l) {
                        return Math.pow(l, u)
                    }
                });
                for (var s = 1; s < u; s++) {
                    for (var m = [new p(u)], n = 0; n < u - s; n++) {
                        m.push(new r)
                    }
                    for (n = 0; n < s; n++) {
                        m.push(new o)
                    }
                    t.push(new q(m))
                }
                t.push(new function () {
                    return function (l) {
                        return Math.pow(1 - l, u)
                    }
                });
                g[u] = t
            }
            return t
        },
        a = function (m, l) {
            for (var o = c(m.length - 1), q = 0, n = 0, p = 0; p < m.length; p++) {
                q += m[p].x * o[p](l);
                n += m[p].y * o[p](l)
            }
            return {
                x: q,
                y: n
            }
        },
        f = function (m, l, o) {
            var q = a(m, l),
                n = 0;
            l = l;
            for (var p = o > 0 ? 1 : -1, r = null; n < Math.abs(o);) {
                l += 0.005 * p;
                r = a(m, l);
                n += Math.sqrt(Math.pow(r.x - q.x, 2) + Math.pow(r.y - q.y, 2));
                q = r
            }
            return {
                point: r,
                location: l
            }
        },
        e = function (m, l) {
            var n = a(m, l),
                o = a(m.slice(0, m.length - 1), l);
            return Math.atan((o.y - n.y) / (o.x - n.x))
        };
    window.jsBezier = {
        distanceFromCurve: h,
        gradientAtPoint: e,
        nearestPointOnCurve: function (m, l) {
            var n = h(m, l);
            return {
                point: i(l, l.length - 1, n.location, null, null),
                location: n.location
            }
        },
        pointOnCurve: a,
        pointAlongCurveFrom: function (m, l, n) {
            return f(m, l, n).point
        },
        perpendicularToCurveAt: function (m, l, n, o) {
            o = o == null ? 0 : o;
            l = f(m, l, o);
            m = e(m, l.location);
            o = Math.atan(-1 / m);
            m = n / 2 * Math.sin(o);
            n = n / 2 * Math.cos(o);
            return [{
                x: l.point.x + n,
                y: l.point.y + m
            }, {
                x: l.point.x - n,
                y: l.point.y - m
            }]
        }
    }
})();