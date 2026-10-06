(() => {
	"use strict";

	const raiz = document.getElementById("horarios");
	if (!raiz) return;

	const BASE = raiz.dataset.baseUrl.replace(/\/+$/, "") + "/";
	const url = (ruta) => BASE + ruta;

	// Una letra por día, en el mismo orden en que los entrega el servidor
	const LETRAS = ["L", "M", "W", "J", "V", "S", "D"];

	const MESES = [
		"Enero",
		"Febrero",
		"Marzo",
		"Abril",
		"Mayo",
		"Junio",
		"Julio",
		"Agosto",
		"Septiembre",
		"Octubre",
		"Noviembre",
		"Diciembre",
	];

	const tabs = document.getElementById("dias-tab");
	const contenido = document.getElementById("dias-contenido");
	const titulo = document.getElementById("semana-titulo");
	const btnAnterior = document.getElementById("semana-anterior");
	const btnSiguiente = document.getElementById("semana-siguiente");

	let controlador = null;

	// ── Helpers ──────────────────────────────────────────────────
	// Crea nodos con textContent (sin riesgo de XSS)
	function el(tag, props = {}, ...hijos) {
		const nodo = document.createElement(tag);
		for (const [k, v] of Object.entries(props)) {
			if (k === "class") nodo.className = v;
			else if (k === "text") nodo.textContent = v;
			else nodo.setAttribute(k, v);
		}
		nodo.append(...hijos.filter(Boolean));
		return nodo;
	}

	// "2026-05-12" -> {a: 2026, m: 5, d: 12} (sin pasar por Date: evita líos de zona horaria)
	function partes(fecha) {
		const [a, m, d] = String(fecha).split("-").map(Number);
		return { a, m, d };
	}

	const hhmm = (hora) => String(hora ?? "").slice(0, 5);

	const fechaLarga = (fecha) => {
		const p = partes(fecha);
		return `${p.d} ${MESES[p.m - 1]} ${p.a}`;
	};

	function tituloSemana(inicio, fin) {
		const i = partes(inicio);
		const f = partes(fin);

		return i.m === f.m
			? `Semana del ${i.d} al ${f.d} ${MESES[f.m - 1]}`
			: `Semana del ${i.d} ${MESES[i.m - 1]} al ${f.d} ${MESES[f.m - 1]}`;
	}

	const mensaje = (texto, extra = "text-secondary") =>
		el("p", { class: `${extra} text-center`, text: texto });

	// ── Render ───────────────────────────────────────────────────
	function tarjeta(bloque) {
		const reservas = Number(bloque.reservas) || 0;
		const cupos = Number(bloque.cupos_maximos) || 0;
		const lleno = cupos > 0 && reservas >= cupos;

		const enlace = `${url("administrador/editarBloque")}?id=${encodeURIComponent(bloque.id_bloque)}`;

		return el(
			"a",
			{ class: "text-decoration-none", href: enlace },
			el(
				"div",
				{ class: "schedule-card mb-3 clickable-card" },
				el(
					"div",
					{ class: "d-flex justify-content-between align-items-center" },

					el(
						"div",
						{},
						el("h5", {
							class: "text-white mb-1",
							text: hhmm(bloque.hora_inicio),
						}),
						el("p", {
							class: "text-white mb-1",
							text: `Grupal - ${bloque.nombre_profesor || "Sin profesor"}`,
						}),
						el("small", {
							class: "text-secondary",
							text: fechaLarga(bloque.fecha),
						}),
					),

					el(
						"div",
						{ class: "text-end" },
						el("p", {
							class: `mb-1 ${lleno ? "text-warning" : "text-secondary"}`,
							text: `${reservas}/${cupos}`,
						}),
						el("small", { class: "text-secondary", text: "Editar →" }),
					),
				),
			),
		);
	}

	function pintarSemana(data) {
		titulo.textContent = tituloSemana(data.semana.inicio, data.semana.fin);
		btnAnterior.dataset.semana = data.semana.anterior;
		btnSiguiente.dataset.semana = data.semana.siguiente;

		// Día activo: hoy si cae en esta semana; si no, el primero
		const indiceActivo = Math.max(
			0,
			data.dias.findIndex((d) => d.fecha === data.hoy),
		);

		tabs.replaceChildren();
		contenido.replaceChildren();

		data.dias.forEach((dia, i) => {
			const activo = i === indiceActivo;
			const idPanel = `dia-${dia.fecha}-pane`;
			const idTab = `dia-${dia.fecha}-tab`;

			// Tab
			const boton = el(
				"button",
				{
					class: "nav-link" + (activo ? " active" : ""),
					id: idTab,
					type: "button",
					role: "tab",
					"data-bs-toggle": "tab",
					"data-bs-target": `#${idPanel}`,
					"aria-controls": idPanel,
					"aria-selected": String(activo),
				},
				el("small", { text: LETRAS[i] ?? "" }),
				el("br"),
				String(partes(dia.fecha).d),
			);

			tabs.append(el("li", { class: "nav-item", role: "presentation" }, boton));

			// Panel con sus bloques
			const panel = el("div", {
				class: "tab-pane fade" + (activo ? " show active" : ""),
				id: idPanel,
				role: "tabpanel",
				"aria-labelledby": idTab,
				tabindex: "0",
			});

			if (dia.bloques.length === 0) {
				panel.append(mensaje("No hay clases"));
			} else {
				panel.append(...dia.bloques.map(tarjeta));
			}

			contenido.append(panel);
		});
	}

	// ── Carga ────────────────────────────────────────────────────
	async function cargar(semana) {
		controlador?.abort(); // cancela una petición anterior pendiente
		controlador = new AbortController();

		contenido.replaceChildren(mensaje("Cargando…"));

		const params = new URLSearchParams();
		if (semana) params.set("semana", semana);

		try {
			const respuesta = await fetch(
				`${url("administrador/apiHorarios")}?${params}`,
				{
					headers: { Accept: "application/json" },
					credentials: "same-origin",
					signal: controlador.signal,
				},
			);

			if (!respuesta.ok) throw new Error(`HTTP ${respuesta.status}`);

			const data = await respuesta.json();
			if (!data.success) throw new Error("El servidor respondió sin éxito");

			pintarSemana(data);
		} catch (error) {
			if (error.name === "AbortError") return;

			console.error("Error al cargar los horarios:", error);
			titulo.textContent = "No se pudo cargar";
			tabs.replaceChildren();
			contenido.replaceChildren(
				mensaje(
					"No se pudieron cargar los horarios. Intenta nuevamente.",
					"text-danger",
				),
			);
		}
	}

	btnAnterior.addEventListener("click", () => {
		if (btnAnterior.dataset.semana) cargar(btnAnterior.dataset.semana);
	});

	btnSiguiente.addEventListener("click", () => {
		if (btnSiguiente.dataset.semana) cargar(btnSiguiente.dataset.semana);
	});

	document.addEventListener("DOMContentLoaded", () => {
		const semana = new URLSearchParams(window.location.search).get("semana");
		cargar(semana);
	});
})();
