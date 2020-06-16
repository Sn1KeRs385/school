const replacer = (date) => {
  return date.replace(' ', 'T');
}

export const DateStringToLocalString = (date) => {
  if(date === null){
    return '';
  }
  return (new Date(replacer(date))).toLocaleString()
}

export const DateStringToLocalDateString = (date) => {
  if(date === null){
    return '';
  }
  return (new Date(replacer(date))).toLocaleDateString()
}

export const DateStringToLocalTimeString = (date) => {
  if(date === null){
    return '';
  }
  return (new Date(replacer(date))).toLocaleTimeString()
}
