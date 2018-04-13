export class CustomUrl extends URL {
	static buildQueryString (paramsObj) {
		return Object.keys(paramsObj).map(k => k + '=' + paramsObj[k]).join('&');
	}
}