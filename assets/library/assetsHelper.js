export function assetPath(assetPath) {
    return assetPath.startsWith('http') ? assetPath : `${yabeWebfont.assets.url}${assetPath}`;
}