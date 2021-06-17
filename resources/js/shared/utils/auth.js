//set and get auth related data from localstorage
export function isLoggedIn() {
    return localStorage.getItem('isLoggedIn') ==  'true'
}
export function logIn() {
    localStorage.setItem('isLoggedIn', 'true')
}
export function logOut() {
    localStorage.setItem('isLoggedIn', 'false')
}
