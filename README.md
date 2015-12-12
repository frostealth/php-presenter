PHP View Presenters
=============

So you have those scenarios where a bit of logic needs to be performed before some data (likely from your entity) 
is displayed from the view.

* Should that logic be hard-coded into the view? **No.**
* Should we instead store the logic in the model? **No again!**

Instead, leverage view presenters. That's what they're for! This package provides one such implementation.

## Installation

Run the [Composer](http://getcomposer.org/download/) command to install the latest stable version:

```bash
composer require frostealth/php-presenter @stable
```

## Usage

The first step is to store your presenters somewhere - anywhere. 
These will be simple objects that do nothing more than format data, as required.

Here's an example of a presenter.

```php
namespace app\presenters;

use frostealth\presenter\Presenter;

/**
 * Class ConcreteEntityPresenter
 *
 * @property-read string $fullName
 * @property-read string $birthDate
 */
class ConcreteModelPresenter extends Presenter
{
    /**
     * @return string
     */
    public function getFullName()
    {
        return implode(' ', [$this->firstName, $this->lastName]);
    }
    
    /**
     * @return string
     */
    public function getBirthDate()
    {
        return date('y.M.d', $this->entity->birthDate);
    }
}
```

Here's an example of an presentable model.

```php
namespace app\models;

use app\presenters\ConcreteModelPresenter;
use frostealth\presenter\interfaces\PresentableInterface;

class ConcreteModel implements PresentableInterface
{
    /** @var string */
    public $firstName;
    
    /** @var string */
    public $lastName;
    
    /** @var string */
    public $birthDate;

    /**
     * @return ConcreteModelPresenter
     */
    public function presenter()
    {
        return new ConcreteModelPresenter($this);
    }
}
```

Now, within your view, you can do:

```php
<dl>
    <dt>Name</dt>
    <dd><?= $model->presenter()->fullName ?></dd>
    
    <dt>Birth Date</dt>
    <dd><?= $model->presenter()->birthDate ?></dd>
</dl>
```

## License

The MIT License (MIT).
See [LICENSE.md](https://github.com/frostealth/php-presenter/blob/master/LICENSE.md) for more information.
