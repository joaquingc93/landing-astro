#!/usr/bin/env node
import { readFileSync, readdirSync, statSync } from "fs";
import { join, extname } from "path";

const distDir = join(process.cwd(), "dist");

function walk(dir, acc = []) {
  for (const entry of readdirSync(dir)) {
    const full = join(dir, entry);
    const st = statSync(full);
    if (st.isDirectory()) walk(full, acc);
    else acc.push(full);
  }
  return acc;
}

function extractImgs(html) {
  const srcRegex = /<img[^>]+src=["']([^"']+)["']/gi;
  const srcsetRegex = /<img[^>]+srcset=["']([^"']+)["']/gi;
  const results = [];
  let m;
  while ((m = srcRegex.exec(html))) results.push({ type: "src", url: m[1] });
  while ((m = srcsetRegex.exec(html))) {
    const parts = m[1].split(/\s*,\s*/).map((s) => s.split(/\s+/)[0]);
    parts.forEach((p) => results.push({ type: "srcset", url: p }));
  }
  return results;
}

function isRemote(u) {
  return /^https?:\/\//i.test(u);
}
function isData(u) {
  return /^data:/i.test(u);
}

const htmlFiles = walk(distDir).filter((f) => extname(f) === ".html");
let total = 0,
  missing = 0;
const missingList = [];

for (const file of htmlFiles) {
  const html = readFileSync(file, "utf8");
  const imgs = extractImgs(html);
  imgs.forEach((img) => {
    total++;
    const url = img.url;
    if (
      isRemote(url) ||
      isData(url) ||
      url.startsWith("mailto:") ||
      url.startsWith("tel:")
    )
      return;
    // normalizar stripping query/hash
    const clean = url.split("?")[0].split("#")[0];
    let path = clean.startsWith("/")
      ? join(distDir, clean.replace(/^\//, ""))
      : join(file.substring(0, file.lastIndexOf("/")), clean);
    if (!path.startsWith(distDir))
      path = join(distDir, clean.replace(/^\//, ""));
    try {
      statSync(path);
    } catch {
      missing++;
      missingList.push({ file, url });
    }
  });
}

if (missing === 0) {
  console.log(`✅ Imagenes OK: ${total} referencias, 0 faltantes.`);
  process.exit(0);
} else {
  console.error(`❌ Faltan ${missing} de ${total}.`);
  missingList
    .slice(0, 50)
    .forEach((m) => console.error(` - ${m.url} (referenciada en ${m.file})`));
  process.exit(1);
}
