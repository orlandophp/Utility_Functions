## Loose Functions are the Smallest Framework

I run into these enough that I wanted to put them somewhere safe:

* `render()` uses the simplest method possible to render a PHP template with an isolated scope.
* `lock()` acquires an exclusive lock on a file or raises a `LockError` exception if it can't.
* `unlock()` releases the lock on the file. These are useful for crons and daemons.

