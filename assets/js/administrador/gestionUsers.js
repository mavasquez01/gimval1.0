(() => {
	"use strict";

	const root = document.getElementById("gestion-users");
	if (!root) return;

	const BASE = root.dataset.baseUrl.replace(/\/+$/, "") + "/";
	const TAB_INICIAL = root.dataset.tabInicial;
	const url = (ruta) => BASE + ruta;

	// ── Configuración: un objeto = un tab ────────────────────────
	const TABS = [
		{
			id: "alumnas",
			titulo: "Alumnas",
			singular: "alumna",
			endpoint: "administrador/apiUsuarios/alumnas",
			urlPerfil: "administrador/detalleUser",
			urlAgregar: "administrador/crearUser",
			mostrarPlan: true,
			etiquetaInactivo: "Inactiva",
		},
		{
			id: "profesores", // coincide con ?tab=profesores
			titulo: "Profesoras",
			singular: "profesora",
			endpoint: "administrador/apiUsuarios/profesoras",
			urlPerfil: "administrador/detalleProfesor",
			urlAgregar: "administrador/crearUser",
			mostrarPlan: false,
			etiquetaInactivo: "Inactivo",
		},
	];

	// ── Helper para crear nodos (textContent => sin XSS) ─────────
	function el(tag, props = {}, ...hijos) {
		const nodo = document.createElement(tag);
		for (const [k, v] of Object.entries(props)) {
			if (k === "class") nodo.className = v;
			else if (k === "text") nodo.textContent = v;
			else if (k.startsWith("on")) nodo.addEventListener(k.slice(2), v);
			else nodo.setAttribute(k, v);
		}
		nodo.append(...hijos.filter(Boolean));
		return nodo;
	}

	const mensaje = (texto, extra = "text-white") =>
		el("p", { class: `text-center py-4 ${extra}`, text: texto });

	function badgePlan(dias) {
		if (dias === null || dias === undefined) return null;

		let texto, clase;
		if (dias < 0) {
			texto = "Plan vencido";
			clase = "bg-danger";
		} else if (dias === 0) {
			texto = "El plan vence hoy";
			clase = "bg-warning text-dark";
		} else if (dias <= 7) {
			texto = `Plan a punto de expirar: faltan ${dias} ${dias === 1 ? "día" : "días"}`;
			clase = "bg-warning text-dark";
		} else {
			texto = "Plan activo";
			clase = "bg-success";
		}

		return el("span", { class: `badge ${clase} mt-2 me-2`, text: texto });
	}

	function tarjeta(tab, it) {
		const nombre = `${it.nombre ?? ""} ${it.apellido ?? ""}`.trim();
		const inactivo = Number(it.activo) === 0;
		const enlace = `${url(tab.urlPerfil)}?rut=${encodeURIComponent(it.rut)}`;

		const badgeInactivo = inactivo
			? el("span", {
					class: "badge bg-secondary mt-2 me-2",
					text: tab.etiquetaInactivo,
				})
			: null;

		const badge = tab.mostrarPlan && !inactivo ? badgePlan(it.dias_plan) : null;

		return el(
			"div",
			{ class: "card mb-3" + (inactivo ? " opacity-75" : "") },
			el(
				"div",
				{ class: "card-body" },
				el(
					"div",
					{ class: "d-flex align-items-center" },
					el("i", {
						class: "bi bi-person-circle fs-1 text-white me-3",
						"aria-hidden": "true",
					}),
					el(
						"div",
						{},
						el("p", { class: "text-white mb-1", text: nombre }),
						el("p", { class: "text-white mb-0", text: `RUT ${it.rut}` }),
						badgeInactivo,
						badge,
						el(
							"a",
							{ class: "d-inline-block text-white mt-2", href: enlace },
							"Ver perfil ",
							el("i", { class: "bi bi-chevron-right", "aria-hidden": "true" }),
						),
					),
				),
			),
		);
	}
	// ── Construye el contenido y la lógica de un tab ─────────────
	function construirPanel(tab, pane) {
		const st = { buscar: "", pagina: 1, cargado: false, abort: null };

		const lista = el("div", { class: "mt-3", "aria-live": "polite" });
		const paginacion = el("div", {
			class: "d-flex justify-content-center my-4",
		});

		const input = el("input", {
			class: "form-control flex-grow-1",
			type: "search",
			placeholder: `Buscar ${tab.singular}`,
			"aria-label": `Buscar ${tab.singular} por nombre, apellido o RUT`,
		});

		const form = el(
			"form",
			{
				class: "d-flex align-items-center gap-2 w-100",
				role: "search",
				onsubmit: (e) => {
					e.preventDefault();
					st.buscar = input.value.trim();
					st.pagina = 1;
					cargar();
				},
			},
			el("i", { class: "bi bi-search text-white", "aria-hidden": "true" }),
			input,
			el("button", {
				class: "btn btn-outline-primary px-4 w-auto",
				type: "submit",
				text: "Buscar",
			}),
		);

		const agregar = el(
			"div",
			{ class: "mt-3 d-grid" },
			el("a", {
				class: "btn btn-outline-primary",
				href: url(tab.urlAgregar),
				text: `Agregar ${tab.singular.charAt(0).toUpperCase()}${tab.singular.slice(1)}`,
			}),
		);

		pane.append(
			el("div", { class: "mt-3 px-4" }, form, agregar),
			lista,
			paginacion,
		);

		function pintarLista(items) {
			if (!items.length) {
				lista.replaceChildren(
					mensaje(`No se encontraron ${tab.titulo.toLowerCase()}.`),
				);
				return;
			}
			lista.replaceChildren(...items.map((it) => tarjeta(tab, it)));
		}

		function pintarPaginacion(actual, total) {
			if (total <= 1) {
				paginacion.replaceChildren();
				return;
			}

			const item = (
				label,
				pagina,
				{ activo = false, deshabilitado = false } = {},
			) =>
				el(
					"li",
					{
						class:
							"page-item" +
							(activo ? " active" : "") +
							(deshabilitado ? " disabled" : ""),
					},
					el("a", {
						class: "page-link",
						href: "#",
						text: label,
						onclick: (e) => {
							e.preventDefault();
							if (activo || deshabilitado) return;
							st.pagina = pagina;
							cargar();
						},
					}),
				);

			const desde = Math.max(1, actual - 2);
			const hasta = Math.min(total, actual + 2);

			const items = [item("‹", actual - 1, { deshabilitado: actual === 1 })];
			for (let p = desde; p <= hasta; p++) {
				items.push(item(String(p), p, { activo: p === actual }));
			}
			items.push(item("›", actual + 1, { deshabilitado: actual === total }));

			paginacion.replaceChildren(
				el(
					"nav",
					{ "aria-label": "Paginación" },
					el(
						"ul",
						{ class: "pagination pagination-sm custom-pagination" },
						...items,
					),
				),
			);
		}

		async function cargar() {
			st.abort?.abort(); // cancela una petición anterior pendiente
			st.abort = new AbortController();
			lista.replaceChildren(mensaje("Cargando…"));

			try {
				const params = new URLSearchParams({
					buscar: st.buscar,
					pagina: st.pagina,
				});
				const resp = await fetch(`${url(tab.endpoint)}?${params}`, {
					headers: {
						"X-Requested-With": "XMLHttpRequest",
						Accept: "application/json",
					},
					credentials: "same-origin",
					signal: st.abort.signal,
				});
				if (!resp.ok) throw new Error(`HTTP ${resp.status}`);

				const data = await resp.json();
				st.pagina = data.pagina;
				st.cargado = true;
				pintarLista(data.items);
				pintarPaginacion(data.pagina, data.paginas);
			} catch (err) {
				if (err.name === "AbortError") return;
				lista.replaceChildren(
					mensaje(
						"No se pudo cargar la lista. Intenta nuevamente.",
						"text-danger",
					),
				);
				paginacion.replaceChildren();
			}
		}

		return {
			cargar,
			cargarSiHaceFalta: () => {
				if (!st.cargado) cargar();
			},
		};
	}

	// ── Genera los tabs ──────────────────────────────────────────
	const nav = document.getElementById("tabs-admin");
	const contenido = document.getElementById("gestion-contenido");
	const tabActivo = TABS.some((t) => t.id === TAB_INICIAL)
		? TAB_INICIAL
		: TABS[0].id;

	TABS.forEach((tab) => {
		const activo = tab.id === tabActivo;

		const boton = el("button", {
			class: "nav-link" + (activo ? " active" : ""),
			id: `${tab.id}-tab`,
			type: "button",
			role: "tab",
			"data-bs-toggle": "tab",
			"data-bs-target": `#${tab.id}-tab-pane`,
			"aria-controls": `${tab.id}-tab-pane`,
			"aria-selected": String(activo),
			text: tab.titulo,
		});
		const primero = tab === TABS[0];
		nav.append(
			el(
				"li",
				{
					class: "nav-item " + (primero ? "me-3 ms-5" : "mx-3"),
					role: "presentation",
				},
				boton,
			),
		);

		const pane = el("div", {
			class: "tab-pane fade" + (activo ? " show active" : ""),
			id: `${tab.id}-tab-pane`,
			role: "tabpanel",
			"aria-labelledby": `${tab.id}-tab`,
			tabindex: "0",
		});
		contenido.append(pane);

		const panel = construirPanel(tab, pane);

		// Carga perezosa: solo pide datos cuando el tab se muestra por primera vez
		boton.addEventListener("shown.bs.tab", panel.cargarSiHaceFalta);
		if (activo) panel.cargar();
	});
})();
