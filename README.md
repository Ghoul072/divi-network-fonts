# Divi Network Fonts

A WordPress plugin that provides network-wide shared custom fonts for Divi theme across a multisite installation.

## What It Does

This plugin allows you to:

- **Share custom fonts across all sites** in a WordPress multisite network
- **Integrate fonts directly into Divi's font dropdown** so they appear alongside standard web-safe fonts
- **Use variable fonts** with full weight range support (100-900)
- **Automatic cache-busting** when fonts are updated

Fonts are loaded on both the frontend and in the Divi Visual Builder.

## Features

- **Zero Configuration Reload** - Update `fonts.json` and changes take effect immediately on the next page load. No cache clearing, no reactivation, no server restart required.
- **Single Source of Truth** - One `fonts.json` file controls fonts across your entire multisite network. Update once, apply everywhere.
- **Variable Font Support** - Full support for modern variable fonts with weight ranges (100-900) in a single file.
- **WYSIWYG Editing** - Fonts load in both the frontend and Divi Visual Builder, so what you see while editing is what visitors see.
- **Automatic Cache Busting** - CSS versioning is tied to `fonts.json` modification time, ensuring browsers always load the latest fonts.
- **Lightweight** - No database queries, no admin UI, no settings pages. Just a simple file-based registry.
- **Native Divi Integration** - Fonts appear directly in Divi's font dropdown alongside built-in web-safe fonts.

## Requirements

- WordPress Multisite installation
- Divi Theme or Divi Builder plugin
- Write access to `wp-content/uploads/fonts/`

## Installation

### 1. Upload the Plugin

Place `divi-network-fonts.php` in one of these locations:

**For network-wide activation (recommended):**

```
wp-content/mu-plugins/divi-network-fonts.php
```

**Disclaimer:** This method does not require explicit network activation in WordPress admin and will not appear in the standard plugin list

**For standard plugin installation:**

```
wp-content/plugins/divi-network-fonts/divi-network-fonts.php
```

Then network-activate manually from the WordPress admin.

### 2. Create the Fonts Directory

Create the fonts directory in your uploads folder:

```bash
mkdir -p wp-content/uploads/fonts/
```

### 3. Add Your Font Files

Upload your font files (`.otf`, `.ttf`, `.woff`, `.woff2`) to:

```
wp-content/uploads/fonts/
```

### 4. Create the Font Registry

Create a `fonts.json` file in the fonts directory:

```
wp-content/uploads/fonts/fonts.json
```

## Configuration

### fonts.json Structure

The `fonts.json` file defines which fonts are available. Here's the schema:

```json
[
  {
    "label": "My Custom Font",
    "family": "MyCustomFont",
    "file": "my-custom-font.woff2",
    "variable": false,
    "character_set": "latin",
    "type": "sans-serif"
  }
]
```

### Field Reference

| Field           | Required | Description                                                                     |
| --------------- | -------- | ------------------------------------------------------------------------------- |
| `label`         | Yes      | Display name in Divi's font dropdown                                            |
| `family`        | Yes      | CSS font-family name (used in stylesheets)                                      |
| `file`          | Yes      | Font filename (relative to the fonts directory)                                 |
| `variable`      | No       | Set to `true` for variable fonts (enables weight range 100-900)                 |
| `character_set` | No       | Character set (default: `latin`)                                                |
| `type`          | No       | Font category: `serif`, `sans-serif`, `monospace`, etc. (default: `sans-serif`) |

### Example: Multiple Fonts

```json
[
  {
    "label": "Inter",
    "family": "Inter",
    "file": "Inter-Variable.woff2",
    "variable": true,
    "character_set": "latin,latin-ext",
    "type": "sans-serif"
  },
  {
    "label": "Playfair Display",
    "family": "PlayfairDisplay",
    "file": "PlayfairDisplay-Regular.woff2",
    "variable": false,
    "character_set": "latin",
    "type": "serif"
  },
  {
    "label": "JetBrains Mono",
    "family": "JetBrainsMono",
    "file": "JetBrainsMono-Variable.woff2",
    "variable": true,
    "character_set": "latin",
    "type": "monospace"
  }
]
```

**Important:** Divi uses the font name from the registry when applying styles. Make sure your `label` matches the `family` if you set both fields so the selected font maps to the generated `@font-face` rule.

## Usage

Once configured, your custom fonts will automatically appear in Divi's font selection dropdown under the standard fonts section. Select them just like any other font.

### Variable Fonts

When `"variable": true` is set, Divi will show all font weights (100-900) as available options. This works with modern variable font files that contain multiple weights in a single file.

### Static Fonts

When `"variable": false` or omitted, only the regular (400) weight is registered. If you need multiple weights of a static font, add separate entries for each weight file.

## File Structure

After installation, your structure should look like:

```
wp-content/
├── mu-plugins/
│   └── divi-network-fonts.php
└── uploads/
    └── fonts/
        ├── fonts.json
        ├── Inter-Variable.woff2
        ├── PlayfairDisplay-Regular.woff2
        └── ...
```

## How It Works

1. **Font Registration**: The plugin hooks into Divi's `et_websafe_fonts` filter to add your fonts to the dropdown
2. **CSS Generation**: On page load, `@font-face` rules are dynamically generated and injected
3. **Cache Busting**: The CSS version is based on `fonts.json` modification time, so changes are immediately reflected

## Troubleshooting

### Fonts not appearing in Divi dropdown

- Verify `fonts.json` exists and is valid JSON
- Check that each font entry has a `label` field
- Clear any caching plugins

### Fonts not loading on frontend

- Verify font files exist in the fonts directory
- Check that `file` paths in `fonts.json` match actual filenames
- Inspect browser console for 404 errors
- Ensure the fonts directory is web-accessible

### Variable font weights not working

- Confirm the font file is actually a variable font
- Set `"variable": true` in the font entry
- Some older browsers may not support variable fonts

## License

MIT License - Copyright (c) 2026 Yaseen

See [LICENSE](LICENSE) for details.
