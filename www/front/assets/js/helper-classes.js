export class RequestTimer {
  constructor(timeout = 1000) {
    this.id = -1
    this.timeout = timeout
  }

  start(callback, timeout = this.timeout) {
    this.clear()
    const localId = setTimeout(async () => {
      if (localId === this.id) {
        await callback()
      }
    }, timeout)
    this.id = localId
  }

  clear() {
    clearTimeout(this.id)
  }
}

export class PaginateList {
  constructor(records = 25) {
    this.items = []
    this.meta = {
      total: 0,
      next_exists: true,
      next_page: 1,
      page: 0,
      last_page: 0,
      records,
    }
  }
}

export class DateValue {
  constructor(params = {}) {
    this.day = Number(params.day) || null
    this.month = Number(params.month) || null
    this.year = Number(params.year) || null
  }
}

export class Updater {
  constructor(timeout) {
    this.timerId = -1
    this.timeout = timeout
  }

  start(callback) {
    if (this.timerId !== -1) return false
    const clock = () => {
      if (this.timerId === -1) return
      callback()
      this.timerId = setTimeout(clock, this.timeout)
    }
    this.timerId = setTimeout(clock, this.timeout)
    return true
  }

  stop() {
    clearTimeout(this.timerId)
  }
}
