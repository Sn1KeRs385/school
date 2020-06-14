const regexEmail = /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/

export class RuleBase {
  constructor(message, name = null) {
    this.message = message
    this.name = name || this.constructor.name
  }

  check(isValid) {
    return {
      isValid,
      message: this.message,
      name: this.name,
    }
  }
}

export class FullDateRule extends RuleBase {
  check(value) {
    const isValid =
      (value.day === null && value.month === null && value.year === null) ||
      (value.day && value.month && value.year)
    return super.check(isValid)
  }
}

export class RequiredRule extends RuleBase {
  check(value) {
    return super.check(Boolean(value))
  }
}

export class EmptyArrayRule extends RuleBase {
  check(value) {
    if (!value) {
      return super.check(false)
    }
    if (typeof value === 'object') {
      return super.check(Object.keys(value).length)
    }
    return super.check(value.length)
  }
}

export class EmailRule extends RuleBase {
  check(value) {
    if (!value) {
      return super.check(true)
    }
    return super.check(regexEmail.test(value.toLowerCase()))
  }
}

export class MinLenStringRule extends RuleBase {
  constructor(message, minLength) {
    super(message)
    this.message = message
    this.minLength = minLength
  }

  check(value) {
    if (!value) {
      return super.check(true)
    }
    return super.check(value.length < this.minLength)
  }
}

export class CustomRule extends RuleBase {
  constructor(message, func, name) {
    super(message, name)
    this.func = func
  }

  check(value) {
    return super.check(this.func(value))
  }
}
