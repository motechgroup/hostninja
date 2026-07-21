const fs = require('fs');
const path = require('path');

const url = 'https://stitch.googleapis.com/mcp';
const headers = {
  'Content-Type': 'application/json',
  'X-Goog-Api-Key': process.env.STITCH_API_KEY || ''
};

const outputDir = path.join(__dirname, 'stitch_assets');
if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true });
}

async function downloadFile(fileUrl, outputPath) {
  const response = await fetch(fileUrl, {
    headers: {
      'X-Goog-Api-Key': process.env.STITCH_API_KEY || ''
    }
  });
  const text = await response.text();
  fs.writeFileSync(outputPath, text, 'utf-8');
}

async function main() {
  const body = JSON.stringify({
    jsonrpc: '2.0',
    method: 'tools/call',
    params: {
      name: 'list_screens',
      arguments: {
        projectId: '7374124843443326675'
      }
    },
    id: 1
  });

  console.log("Fetching screens for project 7374124843443326675 from Stitch API...");

  const response = await fetch(url, {
    method: 'POST',
    headers,
    body
  });

  const data = await response.json();
  if (data.error) {
    console.error('Stitch API Error:', data.error);
    return;
  }

  let screens = [];
  try {
    const textContent = data.result.content[0].text;
    const parsedResult = JSON.parse(textContent);
    screens = parsedResult.screens || [];
  } catch (e) {
    console.error("Failed to parse screens content:", e);
    console.log("Raw result:", JSON.stringify(data, null, 2));
    return;
  }

  console.log(`Found ${screens.length} screens in Stitch project.`);

  for (const screen of screens) {
    console.log(`Screen: ${screen.title} (ID: ${screen.id})`);
    if (screen.htmlCode && screen.htmlCode.downloadUrl) {
      const sanitizedTitle = screen.title.replace(/[^a-z0-9]/gi, '_').toLowerCase();
      const filename = `${sanitizedTitle}.html`;
      const outputPath = path.join(outputDir, filename);
      console.log(`Downloading ${screen.title} to ${filename}...`);
      try {
        await downloadFile(screen.htmlCode.downloadUrl, outputPath);
        console.log(`Downloaded ${screen.title} successfully.`);
      } catch (err) {
        console.error(`Failed to download ${screen.title}:`, err);
      }
    } else {
      console.log(`No htmlCode downloadUrl for ${screen.title}`);
    }
  }

  fs.writeFileSync(path.join(outputDir, 'screens_metadata.json'), JSON.stringify(screens, null, 2));
  console.log("Stitch assets download process complete!");
}

main().catch(console.error);
