const link = document.querySelector('link[rel="icon"]')
const mediaQueryList = matchMedia('(prefers-color-scheme: dark)')

const changeFavicon = () => {
  if(mediaQueryList.matches){
    link.setAttribute('href', 'public/favicon-white.ico')
  } else {
    link.setAttribute('href', 'public/favicon-black.ico')
  }
}

mediaQueryList.addEventListener('change', changeFavicon)
changeFavicon()