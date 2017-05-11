using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using WorkWithMe.BL;

namespace WorkWithMe.BL.Test
{
    [TestClass]
    public class UnitTest1
    {
        [TestMethod]
        public void LoadUserContactsTest()
        {
            CUserContactList list = new CUserContactList();
            list.LoadContactsForUser(Guid.Parse("78B6858A-AB7B-4591-BCEA-69ADC7126A60"));
            Assert.IsNull(list);
            //Assert.IsTrue(list.Count > 0);
        }
    }
}
