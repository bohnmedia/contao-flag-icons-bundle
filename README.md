# Contao Flag Icons Bundle

A small Contao 5 wrapper around [lipis/flag-icons][flag-icons]. It exposes a
single insert tag that renders country flags as `<img>` tags.

## Installation

```bash
composer require bohnmedia/contao-flag-icons-bundle
```

## Usage

```text
{{flag_icon::de}}
```

renders

```html
<img src="/assets/flag-icons/4x3/de.svg"
     alt="Germany"
     width="40"
     height="30"
     class="flag-icon flag-icon--de flag-icon--4x3">
```

The `width` and `height` attributes are always emitted so the browser can
reserve space via the intrinsic aspect ratio (no layout shift while the
SVG loads). The default `width` is `40` for both ratios; only the `height`
follows the ratio (`30` for `4x3`, `40` for `1x1`). Override via `size`
or your own CSS.

The `alt` attribute is the localised country name from Contao's `Countries`
service — German in a German page context, English in an English one, etc.

### Parameters

| Parameter | Default | Description                                                                 |
|-----------|---------|-----------------------------------------------------------------------------|
| `ratio`   | `4x3`   | Aspect ratio of the SVG. Allowed values: `4x3`, `1x1`.                      |
| `size`    | `40`    | Value for the `width` attribute. The `height` attribute is computed from the ratio (e.g. `size=24` on `4x3` → `width="24" height="18"`). |
| `alt`     | —       | Override for the `alt` attribute. Pass an empty string for decorative use.  |

### Examples

```text
{{flag_icon::de}}
{{flag_icon::de::ratio=1x1}}
{{flag_icon::de::ratio=1x1::size=24}}
{{flag_icon::de::alt=Country name}}
{{flag_icon::de::alt=}}
```

If the country code is unknown (no matching SVG in the installed library),
the insert tag renders as an empty string.

### Styling

The bundle ships no CSS. Three BEM classes are emitted on every `<img>`:

- `flag-icon` — block-level hook for all flags
- `flag-icon--{code}` — country-specific modifier (e.g. `flag-icon--de`)
- `flag-icon--{ratio}` — ratio-specific modifier (e.g. `flag-icon--1x1`)

Use these to apply your own styles, for example:

```css
.flag-icon { width: 1em; height: auto; vertical-align: -0.125em; }
.flag-icon--1x1 { border-radius: 50%; }
```

`height: auto` ensures the height re-derives from the intrinsic aspect
ratio when you only set `width` in CSS.

## License

MIT.

[flag-icons]: https://github.com/lipis/flag-icons
