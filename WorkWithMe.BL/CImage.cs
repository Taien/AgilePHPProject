using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using WorkWithMe.PL; 

namespace WorkWithMe.BL
{
    public class CImage
    {
        public byte[] Content { get; set; }

        public CImage() { }

        public CImage(byte[] content)
        {
            Content = content;
        }
        
        public void LoadData(int userImgId)
        {
            using (WorkWithMeDataContext oDC = new WorkWithMeDataContext())
            {
                spGetImageDataResult result = oDC.spGetImageData(userImgId).FirstOrDefault();

                Content = result.ImageContent.ToArray();
            }
        }
    }
}
