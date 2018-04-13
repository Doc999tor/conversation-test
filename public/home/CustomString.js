export class CustomString extends String {
	cutLongTexts(strLength) {
		return this.length > strLength ? this.slice(0, strLength) + '...' : this;
	}
}