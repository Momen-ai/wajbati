import fs from 'node:fs';
import path from 'node:path';

const src = path.resolve('storage/app/public');
const dest = path.resolve('public/storage');

if (fs.existsSync(src)) {
    try {
        let isLinked = false;
        if (fs.existsSync(dest)) {
            const stat = fs.lstatSync(dest);
            isLinked = stat.isSymbolicLink();
        }
        if (!isLinked) {
            fs.cpSync(src, dest, { recursive: true, force: true });
            console.log('Copied storage/app/public to public/storage successfully.');
        } else {
            console.log('public/storage is already linked.');
        }
    } catch (err) {
        console.warn('Storage copy notice:', err.message);
    }
}
