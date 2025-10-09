import fs from 'fs';

const districtsFilePath = './database/seeders/data/location/districts.json';
const provincesFilePath = './database/seeders/data/location/provinces.json';

// Helper function to read and parse JSON
const readJsonFile = (filePath) => {
    try {
        return JSON.parse(fs.readFileSync(filePath, 'utf8'));
    } catch (error) {
        console.error(`Error reading or parsing ${filePath}:`, error);
        return null;
    }
};

// Helper function to write JSON
const writeJsonFile = (filePath, data) => {
    try {
        fs.writeFileSync(filePath, JSON.stringify(data, null, 2), 'utf8');
        console.log(`${filePath} successfully updated!`);
    } catch (error) {
        console.error(`Error writing to ${filePath}:`, error);
    }
};

const run = () => {
    // Create a map of provinces (id -> name)
    const provinces = readJsonFile(provincesFilePath);
    if (!provinces) return;

    const provinceMap = Object.fromEntries(
        provinces.map(province => [province.id, province.name])
    );

    // Read the districts file
    const districts = readJsonFile(districtsFilePath);
    if (!districts) return;

    // Add the province_name to each district
    const updatedDistricts = districts.map(district => ({
        ...district,
        province_name: provinceMap[district.province_id] || null
    }));

    // Write the updated data back
    writeJsonFile(districtsFilePath, updatedDistricts);
};

run();
