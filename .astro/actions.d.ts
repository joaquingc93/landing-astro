declare module "astro:actions" {
	type Actions = typeof import("D:/2-Joaquin/estudio-trabajo/landing-astro/src/actions/index.ts")["server"];

	export const actions: Actions;
}