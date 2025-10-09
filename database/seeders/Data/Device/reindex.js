import fs from 'fs';

const inputFile = 'purchase_sources.json'; // Dosyanızın adını buraya yazın
const startingId = 1;

fs.readFile(inputFile, 'utf8', (err, data) => {
  if (err) {
    console.error('Dosya okuma hatası:', err);
    return;
  }

  const items = JSON.parse(data);

  items.forEach((item, index) => {
    item.id = startingId + index;
  });

  const outputData = JSON.stringify(items, null, 2);

  fs.writeFile(inputFile, outputData, 'utf8', (err) => {
    if (err) {
      console.error('Dosya yazma hatası:', err);
      return;
    }
    console.log('ID\'ler başarıyla güncellendi!');
  });
});
