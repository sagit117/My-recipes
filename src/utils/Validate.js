function testEmail(email) {
  let reg = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/;
  return reg.test(email);
}

function minLength(value) {
  return value.length === 0
}

function testSymbol(value) {
  let reg = /^[a-zа-яё\s]+$/iu; //[А-Я-Ё]/gi;
  return reg.test(value);
}

export {testEmail, minLength, testSymbol}