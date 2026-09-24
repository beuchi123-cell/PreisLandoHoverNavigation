// Lokal: npm install --no-save terser clean-css; node development/build.cjs
const fs = require('fs');
const path = require('path');
const { minify } = require('terser');
const CleanCSS = require('clean-css');
(async () => {
    const base = path.resolve(__dirname, '..');
    const js = fs.readFileSync(path.join(__dirname, 'navigation.js'), 'utf8');
    const css = fs.readFileSync(path.join(__dirname, 'navigation.css'), 'utf8');
    // Keine Umordnung von CSS-Regeln; !important, Fallbacks und Reihenfolge erhalten.
    const result = new CleanCSS({ level: 0, format: false, rebase: false }).minify(css);
    if (result.errors.length) throw new Error(result.errors.join('\n'));
    if (result.warnings.length) throw new Error(result.warnings.join('\n'));
    const script = await minify(js, { compress: false, mangle: false, format: { comments: false, inline_script: true } });
    fs.writeFileSync(path.join(base, 'resources/views/content/HoverNavigationScript.twig'), '<script>\n'+script.code+'\n</script>\n');
    fs.writeFileSync(path.join(base, 'resources/views/content/HoverNavigationStyle.twig'), '<style>\n'+result.styles+'\n</style>\n');
    console.log(JSON.stringify({ javascriptBytes: Buffer.byteLength(script.code), cssBytes: Buffer.byteLength(result.styles) }));
})().catch(error => { console.error(error); process.exitCode = 1; });
