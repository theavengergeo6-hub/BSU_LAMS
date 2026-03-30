const fs = require('fs');

function parseTable(xmlContent) {
    const rows = [];
    const trRegex = /<w:tr[^>]*>([\s\S]*?)<\/w:tr>/g;
    let trMatch;
    while ((trMatch = trRegex.exec(xmlContent)) !== null) {
        const trContent = trMatch[1];
        const cells = [];
        const tcRegex = /<w:tc[^>]*>([\s\S]*?)<\/w:tc>/g;
        let tcMatch;
        while ((tcMatch = tcRegex.exec(trContent)) !== null) {
            const tcContent = tcMatch[1];
            let cellText = "";
            const tRegex = /<w:t(?:[^>]*?)>([^<]*?)<\/w:t>/g;
            let tMatch;
            while ((tMatch = tRegex.exec(tcContent)) !== null) {
                cellText += tMatch[1];
            }
            cells.push(cellText.trim());
        }
        if (cells.length > 0) {
            rows.push(cells);
        }
    }
    return rows;
}

const folders = ['hot_kitchen', 'fb_services', 'laundry'];
folders.forEach(folder => {
    const docPath = `c:/xampp/htdocs/BSU_Kitchen/documents/unzipped/${folder}/word/document.xml`;
    if (fs.existsSync(docPath)) {
        const xml = fs.readFileSync(docPath, 'utf8');
        const table = parseTable(xml);
        fs.writeFileSync(`c:/xampp/htdocs/BSU_Kitchen/documents/unzipped/${folder}_table.json`, JSON.stringify(table, null, 2));
        console.log(`Parsed ${folder} with ${table.length} rows`);
    } else {
        console.log(`File not found: ${docPath}`);
    }
});
