const fs = require('fs');
const file = 'd:/PROJEK/BBM/resources/views/admin/kendaraans/index.blade.php';
let content = fs.readFileSync(file, 'utf8');
content = content.replace(/<template\s*x-teleport="body">\s*/g, '');
content = content.replace(/<\/template>\s*/g, '');
fs.writeFileSync(file, content);
console.log('Fixed x-teleport');
